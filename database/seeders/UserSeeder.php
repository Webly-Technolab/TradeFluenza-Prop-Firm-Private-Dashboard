<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@tradefluenza.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create propfirm users
        // User::create([
        //     'name' => 'Prop Firm 1',
        //     'email' => 'propfirm1@example.com',
        //     'password' => Hash::make('password'),
        //     'role' => 'propfirm',
        //     'propfirm_name' => 'Alpha Trading Firm',
        //     'propfirm_email' => 'contact@alphatrading.com',
        //     'propfirm_mobile' => '+1234567890',
        // ]);

        // User::create([
        //     'name' => 'Prop Firm 2',
        //     'email' => 'propfirm2@example.com',
        //     'password' => Hash::make('password'),
        //     'role' => 'propfirm',
        //     'propfirm_name' => 'Beta Capital Partners',
        //     'propfirm_email' => 'info@betacapital.com',
        //     'propfirm_mobile' => '+0987654321',
        // ]);
    }
}
