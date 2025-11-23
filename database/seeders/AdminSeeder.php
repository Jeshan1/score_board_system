<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@example.com')->first();
        if (!$admin) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => Hash::make('password123'), // change to your preferred password
                'role' => 'admin',
            ]);

            $this->command->info('Default admin user created: admin@example.com / password123');
        } else {
            $this->command->info('Admin user already exists!');
        }
    }
}
