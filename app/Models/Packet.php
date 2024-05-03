<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Packet extends Model
{
    use HasFactory;

    protected $fillable = [
        'ppp_profile_id',
        'name',
        'speed_limit',
        'price'
    ];
}
