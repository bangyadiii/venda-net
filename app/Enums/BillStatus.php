<?php

namespace App\Enums;

enum BillStatus: string
{
    case PAID = 'paid';
    case UNPAID = 'unpaid';
}
