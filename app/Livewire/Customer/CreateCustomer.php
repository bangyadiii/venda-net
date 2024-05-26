<?php

namespace App\Livewire\Customer;

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
use LaravelDaily\Invoices\Invoice;
use Livewire\Component;

class CreateCustomer extends Component
{
    public Collection $plans;
    public CustomerForm $form;

    public function mount()
    {
        $this->plans = Plan::with('router')->get();
    }

    public function render()
    {
        return view('livewire.customer.create-customer');
    }

    public function store()
    {
        $this->form->validate();
        $plan = Plan::with('router')
            ->findOrFail($this->form->plan_id);

        $router = $plan->router;
        $customer = new Customer();
        $customer->fill($this->form->only(
            Customer::make()->getFillable()
        ));

        if ($this->form->secret_type === 'add_secret') {
            try {
                $client = Router::getClient($router->host, $router->username, $router->password);
                $id = Secret::addSecret(
                    $client,
                    $this->form->secret_username,
                    $this->form->secret_password,
                    $this->form->ppp_service,
                    $plan->ppp_profile_id,
                    $this->form->local_address,
                    $this->form->remote_address,
                    $this->form->ip_type,
                );
                \throw_if(!$id, new Exception('Failed to create secret'));

                $customer->secret_id = $id;
            } catch (\Throwable $th) {
                $this->dispatch('toast', title: $th->getMessage(), type: 'error');
                return redirect()->back();
            }
        } else {
            $customer->secret_id = '';
        }

        $customer->save();

        $isolirDate = Carbon::createFromDate(now()->year, now()->month, $customer->isolir_date);
        if ($isolirDate->isPast()) {
            $isolirDate->addMonth();
        }

        $tax = (int) Setting::where('key', 'ppn')->first()->value ?? 0;
        $bill = $customer->bills()->create([
            'due_date' => $isolirDate,
            'plan_id' => $customer->plan_id,
            'total_amount' => ($plan->price - $this->form->discount) * ($tax / 100 + 1),
            'tax_rate' => $tax,
            'discount' => $this->form->discount,
            'status' => BillStatus::UNPAID,
        ]);
        $bill->invoice_link = $this->createInvoice($customer, $bill, $plan);
        $bill->save();

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