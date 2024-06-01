<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\Router;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $routers = Router::all();
        
        Plan::factory()
            ->recycle($routers)
            ->count(10)
            ->create();
    }
}
