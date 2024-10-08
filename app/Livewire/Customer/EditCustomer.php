<?php

namespace App\Livewire\Customer;

use App\Classes\Invoice;
use App\Enums\BillStatus;
use App\Livewire\Forms\CustomerForm;
use App\Models\Bill;
use App\Models\Customer;
use App\Models\Plan;
use App\Models\Profile;
use App\Models\Router;
use App\Models\Secret;
use App\Models\Setting;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use Livewire\Component;

class EditCustomer extends Component
{
    public Collection $plans;
    public CustomerForm $form;
    public ?Customer $customer;
    public ?Router $router;

    public function mount(Customer $customer)
    {
        $this->customer = $customer->load('plan.router');
        $this->plans = Plan::with('router')->get();
        $this->router = $this->customer?->plan?->router ?? null;

        $this->form->setCustomer($this->customer);
        if ($this->customer->secret_id === null) return;

        try {
            $client = Router::getClient($this->router->host, $this->router->username, $this->router->password);

            $secret = Secret::getSecret($client, $this->customer->secret_id);
            $this->form->secret_password = $secret['password'];
            $this->form->secret_username = $secret['name'];
            $this->form->ppp_service = $secret['service'];
            $this->form->local_address = $secret['local-address'] ?? null;
            $this->form->remote_address = $secret['remote-address'] ?? null;
        } catch (\Throwable $th) {
            $this->dispatch('toast', title: $th->getMessage(), type: 'error');
        }
    }

    public function render()
    {
        return view('livewire.customer.edit-customer');
    }


    public function store()
    {
        $this->form->validate();
        $this->customer->fill($this->form->all());

        if (!$this->customer->auto_isolir) {
            $this->customer->isolir_date = null;
        }
        if ($this->form->isolir_date == 'last_day') {
            $this->customer->isolir_date = 'last_day';
        }

        if ($this->form->plan_id) {
            try {
                $plan = Plan::with('router')
                    ->findOrFail($this->form->plan_id);

                $router = $plan->router;

                $client = Router::getClient($router->host, $router->username, $router->password);
                if ($this->customer->secret_id === null) {
                    $id = Secret::addSecret(
                        client: $client,
                        username: $this->form->secret_username,
                        pw: $this->form->secret_password,
                        service: $this->form->ppp_service,
                        profile: $plan->ppp_profile_id,
                        local: $this->form->local_address,
                        remote: $this->form->remote_address,
                    );
                    \throw_if(!$id, new Exception('Failed to create secret'));

                    $this->customer->secret_id = $id;
                } else {
                    Secret::getSecret($client, $this->customer->secret_id);

                    $values = [
                        'name' => $this->form->secret_username,
                        'password' => $this->form->secret_password,
                        'service' => $this->form->ppp_service,
                        'profile' => $plan->ppp_profile_id,
                        'remote-address' => $this->form->remote_address,
                        'local-address' => $this->form->local_address,
                    ];

                    $id = Secret::updateSecret(
                        $client,
                        $this->customer->secret_id,
                        $values
                    );
                    \throw_if(!$id, new Exception('Failed to update secret'));
                }
            } catch (\Throwable $th) {
                $this->dispatch('toast', title: $th->getMessage(), type: 'error');
                return redirect()->back();
            }
        }

        $this->customer->save();
        $unPaidBills =  Bill::query()
            ->where('customer_id', $this->customer->id)
            ->where('status', BillStatus::UNPAID)
            ->get();

        $date = isset($this->customer->isolir_date) && $this->customer->isolir_date == 'last_day' ?
            now()->endOfMonth() : $this->customer->isolir_date;

        $isolirDate = Carbon::createFromDate(now()->year, now()->month, $date);
        if ($isolirDate->isPast()) {
            $isolirDate->addMonth();
        }

        $tax = (int) Setting::where('key', 'ppn')->first()->value ?? 0;
        if ($unPaidBills->isEmpty()) {
            $bill = new Bill([
                'due_date' => $isolirDate,
                'plan_id' => $this->customer->plan_id,
                'discount' => $this->form->discount,
                'total_amount' => ($plan->price - $this->form->discount) * ($tax / 100 + 1),
                'customer_id' => $this->customer->id,
            ]);

            $bill->invoice_link = $this->createInvoice($this->customer, $bill, $plan);
            $bill->save();
        }

        /** @var Bill $unPaidBill */
        foreach ($unPaidBills as $unPaidBill) {
            $unPaidBill->fill([
                'due_date' => $isolirDate,
                'plan_id' => $this->customer->plan_id,
                'discount' => $this->form->discount,
                'total_amount' => ($plan->price - $this->form->discount) * ($tax / 100 + 1),
            ]);

            $unPaidBill->invoice_link = $this->createInvoice($this->customer, $unPaidBill, $plan);
            $unPaidBill->save();
        }


        $this->dispatch('toast', title: 'Customer updated successfully', type: 'success');

        return $this->redirectRoute('customers.index', navigate: true);
    }

    private function createInvoice(Customer $customer, Bill $bill, Plan $plan): string
    {
        $buyer = new Buyer([
            'name'          => $customer->customer_name,
            'custom_fields' => [
                'phone' => $customer->phone_number,
                'address' => $customer->address,
            ],
        ]);

        $item = InvoiceItem::make($plan->name)
            ->pricePerUnit($plan->price)
            ->discount($bill->discount);

        $invoice = Invoice::make()
            ->buyer($buyer)
            ->taxRate($bill->tax_rate)
            ->addItem($item)
            ->filename($customer->id . '_' . $bill->id)
            ->save('public');

        return $invoice->url();
    }
}
