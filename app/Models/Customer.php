<?php

namespace App\Models;

use App\Enums\InstallmentStatus;
use App\Enums\ServiceStatus;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

class Customer extends Model
{
    use HasFactory;
    use Notifiable;

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
        'isolir_date',
        'secret_id',
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = \random_int(100000, 9999999);
        });
    }

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'installment_status' => InstallmentStatus::class,
        'service_status' => ServiceStatus::class,
    ];

    public function setActiveDateAttribute($value)
    {
        if ($value instanceof DateTime) {
            $this->attributes['active_date'] = Carbon::parse($value);
            return;
        }

        if (\is_string($value) && strlen($value)) {
            $this->attributes['active_date'] = Carbon::createFromFormat('Y-m-d', $value);
        } else {
            $this->attributes['active_date'] = null;
        }
    }

    public function setIsolirDateAttribute($value)
    {
        if (!empty($value) && is_numeric($value)) {
            $this->attributes['isolir_date'] = $value;
        } else {
            $this->attributes['isolir_date'] = null;
        }
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function bills()
    {
        return $this->hasMany(Bill::class);
    }
}
