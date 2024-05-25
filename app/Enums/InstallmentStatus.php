<?php

namespace App\Enums;

enum InstallmentStatus: string
{
    case NOT_INSTALLED = 'not_installed';
    case INSTALLED = 'installed';
}
