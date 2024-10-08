<?php

namespace App\Livewire\Transaction;

use App\Enums\BillStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\ServiceStatus;
use App\Jobs\UnisolateCustomerJob;
use App\Livewire\Forms\TransactionForm;
use App\Models\Bill;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Router;
use App\Models\Secret;
use App\Models\Setting;
use Carbon\Carbon;
use Livewire\Component;

class CreateTransaction extends Component
{
    public TransactionForm $form;
    public ?Bill $currentBill;
    public $customers;
    public $customer_id;
    public $customer;
    public $phone_number;
    public $address;
    public $secret_username;
    public $plan_name;
    public $plan_price;
    public $isolir_month;
    public $nominal;
    public $period;
    public $total_ppn;
    public $grand_total;

    public function mount()
    {
        $this->customers = Customer::where('service_status', ServiceStatus::ACTIVE)->get();
        $tax = (int) Setting::where('key', 'ppn')->first()->value ?? 0;
        if ($this->customer_id) {
            $this->customer = Customer::with(['bills', 'plan'])->find($this->customer_id);
            $this->form->tax_rate = $tax;
        }
    }

    public function render()
    {
        return view('livewire.transaction.create-transaction');
    }

    public function store()
    {
        if (!$this->currentBill || $this->currentBill->status != BillStatus::UNPAID) {
            $this->dispatch('toast', title: 'Tagihan tidak ditemukan', type: 'error');
            return \redirect()->back();
        }
        $customer = Customer::find($this->customer_id);

        \dispatch(new UnisolateCustomerJob($customer));

        $payment = Payment::query()->updateOrCreate([
            'bill_id' => $this->currentBill->id,
        ], [
            'amount' => $this->grand_total,
            'status' => PaymentStatus::SUCCESS,
            'method' => PaymentMethod::CASH,
            'payment_date' => now(),
        ]);

        if (!$payment) {
            $this->dispatch('toast', title: 'Gagal menyimpan transaksi', type: 'error');
            return $this->redirect('transactions.create', navigate: true);
        }

        $this->currentBill->discount = $this->form->discount;
        $this->currentBill->total_amount = $this->grand_total;
        $this->currentBill->status = BillStatus::PAID;
        $this->currentBill->save();
        $this->resetElement();

        $this->dispatch('toast', title: 'Berhasil disimpan');
        return $this->redirectRoute('transactions.index', navigate: true);
    }

    public function updated($name, $value)
    {
        if ($name == 'form.discount') {
            if ($this->plan_price < (int) $value) {
                $this->dispatch('toast', title: 'Diskon tidak boleh melebihi harga paket', type: 'error');
                $this->form->discount = $this->plan_price;
            }

            $this->nominal = $this->plan_price - (int) $this->form->discount;
            $this->total_ppn = $this->nominal * ($this->form->tax_rate / 100);
            $this->grand_total = $this->nominal + $this->total_ppn;
        }
    }

    public function updatedCustomerId()
    {
        if (!$this->customer_id) {
            $this->resetElement();
            return;
        }

        $this->customer = Customer::with(['plan', 'router'])
            ->find($this->customer_id);

        if (!$this->customer) {
            $this->dispatch('toast', title: 'Pelanggan tidak ditemukan', type: 'error');
            $this->customer_id = null;
            return;
        }
        $router = $this->customer->router;
        try {
            $client = Router::getClient($router->host, $router->username, $router->password);
            $this->secret_username = Secret::getSecret($client, $this->customer->secret_id)['name'];
        } catch (\Throwable $th) {
        }

        $this->currentBill = Bill::query()
            ->where('customer_id', $this->customer_id)
            ->where('total_amount', '>', 0)
            ->where('status', BillStatus::UNPAID)
            ->first();

        if (!$this->currentBill) {
            info('create new bill');
            $lastBill = Bill::query()
                ->where('customer_id', $this->customer_id)
                ->latest()->first();

            $tax = (int) Setting::where('key', 'ppn')->first()->value ?? 0;
            $this->currentBill = Bill::create([
                'customer_id' => $this->customer_id,
                'due_date' => $lastBill ? Carbon::parse($lastBill->due_date)->addMonth() : Carbon::createFromDate(now()->year, now()->month, $this->customer->isolir_date),
                'plan_id' => $this->customer->plan_id,
                'discount' => 0,
                'tax_rate' => $tax,
                'status' => BillStatus::UNPAID,
                'total_amount' => $this->customer->plan->price * ($tax / 100 + 1),
            ]);
        }

        $this->phone_number = $this->customer->phone_number;
        $this->address = $this->customer->address;
        $this->secret_username = $this->customer->secret_username;
        $this->plan_name = $this->customer->plan->name;
        $this->plan_price = $this->customer->plan->price;
        $this->form->discount = $this->currentBill->discount;
        $this->isolir_month = Carbon::parse($this->currentBill->due_date)->format('F Y');
        $this->nominal = $this->plan_price - $this->form->discount;
        $this->period = Carbon::parse($this->currentBill->due_date)->subMonth()->addDay()->format('d F Y') . ' - ' . Carbon::parse($this->currentBill->due_date)->format('d F Y');
        $this->form->tax_rate =  $this->currentBill->tax_rate;
        $this->total_ppn =  $this->nominal * ($this->form->tax_rate / 100);
        $this->grand_total = $this->nominal + $this->total_ppn;
    }

    private function resetElement()
    {
        $this->form->reset();
        $this->phone_number = null;
        $this->address = null;
        $this->secret_username = null;
        $this->plan_name = null;
        $this->plan_price = null;
        $this->isolir_month = null;
        $this->nominal = null;
        $this->period = null;
        $this->total_ppn = null;
        $this->grand_total = null;
        $this->currentBill = null;
    }
}
