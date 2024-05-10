<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'secret_id',
        'customer_name',
        'phone_number',
        'active_date',
        'invoice_date',
        'ppp_username',
        'ppp_password',
        'plan_id',
    ];

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
