<?php
namespace App\Enums;

enum PaymentStatus: string
{
    case SUCCESS = 'success';
    case PENDING = 'pending';
    case FAILED = 'failed';
}
