<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'customer_id',
        'plan_id',
        'discount',
        'tax_rate',
        'total_amount',
        'due_date',
        'status',
        'invoice_link',
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = \random_int(100000, 9999999);
        });
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
