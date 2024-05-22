<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()
            ->where('username', 'admin')
            ->firstOrCreate([
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'username' => 'admin',
                'password' => Hash::make('password'),
            ]);

        $this->call([
            SettingSeeder::class,
        ]);
    }
}
