<?php

namespace App\Livewire\Customer;

use App\Enums\BillStatus;
use App\Livewire\Forms\CustomerForm;
use App\Models\Bill;
use App\Models\Customer;
use App\Models\Plan;
use App\Models\Router;
use App\Models\Setting;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use LaravelDaily\Invoices\Invoice;
use Livewire\Component;
use RouterOS\Query;

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
        if ($this->form->secret_type === 'add_secret') {
            try {
                $client = Router::getClient($router->host, $router->username, $router->password);
                $query = new Query('/ppp/secret/add');
                $query->add('=name=' . $this->form->secret_username)
                    ->add('=password=' . $this->form->secret_password)
                    ->add('=service=' . $this->form->ppp_service)
                    ->add('=profile=' . $plan->ppp_profile_id);
                if ($this->form->ip_type === 'remote_address') {
                    $query->add('=remote-address=' . $this->form->remote_address);
                    $query->add('=local-address=' . $this->form->local_address);
                }
                $response = $client->query($query)->read();
                if (!isset($response['after']['ret'])) {
                    throw new Exception($response['after']['message'] ?? 'Failed to create customer');
                }
            } catch (\Throwable $th) {
                $this->dispatch('toast', title: $th->getMessage(), type: 'danger');
                return redirect()->back();
            }
        }

        $customer->fill($this->form->only(
            Customer::make()->getFillable()
        ));
        $customer->secret_id = $response['after']['ret'];
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
