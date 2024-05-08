<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    public $fillable = [
        'transaction_id',
        'order_id',
        'customer_id',
        'payment_type',
        'transaction_time',
        'transaction_status',
        'fraud_status',
        'status_message',
        'signature_key',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
