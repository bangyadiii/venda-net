<?php

namespace App\Livewire\Analytics;

use App\Enums\BillStatus;
use App\Models\Customer;
use App\Models\Router;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AnalyticIndex extends Component
{
    public ?Router $router;

    public function render()
    {
        $customer = Customer::count();
        $paymentComplete = Customer::with('payments')
            ->whereHas('bills', function ($query) {
                $query->where(DB::raw('DATE(due_date)'), '<', now())
                    ->where('status', BillStatus::PAID);
            })->count();
        $suspended = Customer::where('service_status', 'suspended')->count();

        if (Router::count() <= 0) {
            return view('livewire.analytics.index', compact('customer', 'paymentComplete'));
        }

        try {
            $client = Router::getLastClient();
            $secret = Router::getPPPSecret($client);
            $onlineSecret = Router::getPPPSecret($client);
            if ($secret == null || $onlineSecret == null) {
                return view('livewire.analytics.index', compact('customer', 'paymentComplete'));
            }

            return view('livewire.analytics.index', compact('customer', 'paymentComplete', 'secret', 'onlineSecret'));
        } catch (\Throwable $th) {
            return view('livewire.analytics.index', compact('customer', 'paymentComplete'));
        }
    }
}
