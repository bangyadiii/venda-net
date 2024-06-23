<?php

namespace App\Livewire\Customer;

use App\Classes\Invoice;
use App\Enums\BillStatus;
use App\Livewire\Forms\CustomerForm;
use App\Models\Bill;
use App\Models\Customer;
use App\Models\Plan;
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
        $this->router = $this->customer->plan->router;

        $this->form->setCustomer($this->customer);
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

        if ($this->form->plan_id) {
            try {
                $plan = Plan::with('router')
                    ->findOrFail($this->form->plan_id);

                $router = $plan->router;

                $client = Router::getClient($router->host, $router->username, $router->password);
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

                $this->customer->secret_id = $id;
            } catch (\Throwable $th) {
                $this->dispatch('toast', title: $th->getMessage(), type: 'error');
                return redirect()->back();
            }
        }

        $this->customer->save();
        $unPaidBills =  Bill::query()
            ->where('customer_id', $this->customer->id)
            ->where('status', BillStatus::UNPAID)
            ->first();
        // TOOD: check more detail about this
        $isolirDate = Carbon::createFromDate(now()->year, now()->month, $this->customer->isolir_date);
        if ($isolirDate->isPast()) {
            $isolirDate->addMonth();
        }

        $tax = (int) Setting::where('key', 'ppn')->first()->value ?? 0;
        $unPaidBills->fill([
            'due_date' => $isolirDate,
            'plan_id' => $this->customer->plan_id,
            'discount' => $this->form->discount,
            'total_amount' => ($plan->price - $this->form->discount) * ($tax / 100 + 1),
        ]);

        $unPaidBills->invoice_link = $this->createInvoice($this->customer, $unPaidBills, $plan);
        $unPaidBills->save();

        $this->dispatch('toast', title: 'Customer created successfully', type: 'success');

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
