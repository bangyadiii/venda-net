<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-admin {--default}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creating an admin user.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('default')) {
            User::updateOrCreate([
                'username' => 'admin',
            ], [
                'name' => 'Admin',
                'password' => Hash::make('password'),
            ]);
            $this->info('Default admin user created successfully!');
            return Command::SUCCESS;
        }

        $pwd = $this->secret('To create an admin user, you need to enter the password. Enter the password to continue.');
        if ($pwd !== 'PASSWORD###') {
            $this->error('Invalid password!');
            return Command::FAILURE;
        }

        $name = $this->ask('Enter your name', 'Admin');
        $username = $this->ask('Enter your username', 'admin');
        $password = $this->secret('Enter your password', 'password');

        if (User::where('username', $username)->exists()) {
            $this->error('User already exists!');
            return Command::FAILURE;
        }

        $admin = new User();
        $admin->name = $name;
        $admin->username = $username;
        $admin->password = Hash::make($password);
        $admin->save();

        $this->info('Admin user created successfully!');
        return Command::SUCCESS;
    }
}
