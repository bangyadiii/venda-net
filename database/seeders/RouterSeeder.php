<?php

namespace Database\Seeders;

use App\Models\Router;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RouterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Router::factory()
            ->count(2)
            ->create();
    }
}
