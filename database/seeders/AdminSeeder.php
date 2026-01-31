<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if admin already exists
        $adminExists = User::where('email', 'admin@igms.local')->exists();

        if (!$adminExists) {
            User::create([
                'name' => 'Super Admin',
                'email' => 'admin@igms.local',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
            ]);

            $this->command->info('Super Admin account created successfully!');
            $this->command->info('Email: admin@igms.local');
            $this->command->info('Password: password');
        } else {
            $this->command->warn('Super Admin account already exists.');
        }
    }
}
