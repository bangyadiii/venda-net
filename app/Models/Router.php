<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Router extends Model
{
    use HasFactory;

    protected $fillable = [
        'host',
        'username',
        'password',

        'auto_isolir',
        'isolir_action',
        'isolir_profile_id'
    ];
}
