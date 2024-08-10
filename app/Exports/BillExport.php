<?php

namespace App\Exports;

use App\Enums\BillStatus;
use App\Enums\PaymentMethod;
use App\Models\Bill;
use Carbon\Carbon;
use Illuminate\Support\Facades\Date;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BillExport implements FromCollection, WithHeadings, WithMapping
{
    public $bills;

    public function __construct($bills)
    {
        $this->bills = $bills;
    }

    public function collection()
    {
        return Bill::query()
            ->with(['customer', 'plan', 'payment'])
            ->whereIn('id', $this->bills)->get();
    }

    /**
     * @param Bill $bill
     */
    public function map($bill): array
    {
        return [
            $bill->customer->id,
            $bill->customer->customer_name,
            $bill->plan->name,
            $bill->plan->price,
            Date::parse($bill->dude_date)->format('F Y'),
            $bill->discount ?? 0,
            $bill->tax_rate,
            $bill->total_amount,
            $bill->status == BillStatus::PAID ? 'Lunas' : 'Belum Lunas',
            $bill->payment?->payment_date ?? '-',
            $bill->payment?->method == PaymentMethod::CASH ? 'Tunai' : 'Midtrans',
        ];
    }

    public function headings(): array
    {
        return [
            'Nomor Pelanggan',
            'Nama',
            'Paket',
            'Tarif',
            'Tagihan',
            'Diskon',
            'PPN',
            'Total',
            'Status',
            'Tanggal Pembayaran',
            'Metode',
        ];
    }
}
