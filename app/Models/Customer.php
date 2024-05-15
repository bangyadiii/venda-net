<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Customer extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'customer_name',
        'phone_number',
        'address',
        'plan_id',
        'installment_status',
        'service_status',
        'active_date',
        'payment_deadline',
        'secret_username',
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = \random_int(100000, 999999);
        });
    }

    public function setActiveDateAttribute($value)
    {
        if (strlen($value)) {
            $this->attributes['active_date'] = Carbon::createFromFormat('d/m/Y', $value);
        } else {
            $this->attributes['active_date'] = null;
        }
    }

    public function setPaymentDeadlineAttribute($value)
    {
        if (strlen($value) && is_numeric($value)) {
            $this->attributes['payment_deadline'] = $value;
        } else {
            $this->attributes['payment_deadline'] = null;
        }
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
