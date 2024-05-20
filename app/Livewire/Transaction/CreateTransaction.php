<?php

namespace App\Livewire\Transaction;

use App\Livewire\Forms\TransactionForm;
use App\Models\Bill;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\PaymentLog;
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
        $this->customers = Customer::all();
        if ($this->customer_id) {
            $this->customer = Customer::with(['bills', 'plan'])->find($this->customer_id);
        }
    }

    public function render()
    {
        return view('livewire.transaction.create-transaction');
    }

    public function store()
    {
        if (!$this->currentBill || $this->currentBill->status !== 'unpaid') {
            $this->dispatch('toast', title: 'Tagihan tidak ditemukan');
            return \redirect()->back();
        }

        $payment = Payment::create([
            'bill_id' => $this->currentBill->id,
            'amount' => $this->grand_total,
            'status' => 'paid',
            'method' => 'cash',
            'payment_date' => now(),
        ]);

        if (!$payment) {
            $this->dispatch('toast', title: 'Gagal menyimpan transaksi');
            return \redirect()->back();
        }

        PaymentLog::create([
            'payment_id' => $payment->id,
            'status' => 'success',
            'message' => $this->form->note,
        ]);
        $this->currentBill->status = 'paid';
        $this->currentBill->save();
        $this->resetElement();

        $this->dispatch('toast', title: 'Berhasil disimpan');
        return $this->redirectRoute('transactions.index', navigate: true);
    }

    public function updatedCustomerId()
    {
        if (!$this->customer_id) {
            $this->resetElement();
            return;
        }

        $this->customer = Customer::with('plan')
            ->where('service_status', 'active')
            ->find($this->customer_id);
        $this->currentBill = Bill::query()
            ->where('customer_id', $this->customer_id)
            ->where('status', 'unpaid')
            ->first();
        if (!$this->currentBill) {
            info('create new bill');
            $lastBill = Bill::query()
                ->where('customer_id', $this->customer_id)
                ->latest()->first();

            $this->currentBill = Bill::create([
                'customer_id' => $this->customer_id,
                'due_date' => $lastBill ? Carbon::parse($lastBill->due_date)->addMonth() : Carbon::createFromDate(now()->year, now()->month, $this->customer->isolir_date),
                'plan_id' => $this->customer->plan_id,
                'total_amount' => $this->customer->plan->price,
                'discount' => 0,
                'tax_rate' => 11,
                'status' => 'unpaid',
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
        $this->total_ppn =  $this->nominal * ($this->form->tax_rate / 100);
        $this->grand_total = $this->nominal + $this->total_ppn;
    }

    private function resetElement()
    {
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
