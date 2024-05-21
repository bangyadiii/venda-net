<?php

namespace App\Classes;

use Carbon\CarbonInterface;
use LaravelDaily\Invoices\Invoice as LaravelDailyInvoice;

class Invoice extends LaravelDailyInvoice
{
    /**
     * @var CarbonInterface
     */
    public $dueDate;

    public function __construct()
    {
        parent::__construct();
        $this->dueDate = $this->date;
    }

    public function dueDate(CarbonInterface $dueDate)
    {
        $this->dueDate = $dueDate;
        return $this;
    }

    public function getDueDate()
    {
        return $this->dueDate->format($this->date_format);
    }
}
