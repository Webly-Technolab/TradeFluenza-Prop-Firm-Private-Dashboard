<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Payout;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PayoutSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@tradefluenza.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $this->command->info('Admin user created: admin@tradefluenza.com / password');

        // Create sample propfirm users
        $propfirms = [
            [
                'name' => 'Alpha Trading Firm',
                'email' => 'alpha@propfirm.com',
                'password' => Hash::make('password'),
                'role' => 'propfirm',
                'propfirm_name' => 'Alpha Trading Firm',
                'propfirm_email' => 'admin@alphatrading.com',
                'propfirm_mobile' => '+1234567890',
                'api_token' => bin2hex(random_bytes(32)),
            ],
            [
                'name' => 'Beta Capital',
                'email' => 'beta@propfirm.com',
                'password' => Hash::make('password'),
                'role' => 'propfirm',
                'propfirm_name' => 'Beta Capital',
                'propfirm_email' => 'contact@betacapital.com',
                'propfirm_mobile' => '+0987654321',
                'api_token' => bin2hex(random_bytes(32)),
            ],
            [
                'name' => 'Gamma Traders',
                'email' => 'gamma@propfirm.com',
                'password' => Hash::make('password'),
                'role' => 'propfirm',
                'propfirm_name' => 'Gamma Traders',
                'propfirm_email' => 'info@gammatraders.com',
                'propfirm_mobile' => '+1122334455',
                'api_token' => bin2hex(random_bytes(32)),
            ],
        ];

        foreach ($propfirms as $propfirmData) {
            $propfirm = User::create($propfirmData);

            // Create sample payouts for each propfirm
            $this->createSamplePayouts($propfirm);
        }

        $this->command->info('');
        $this->command->info('Payout system seeded successfully!');
        $this->command->info('Created 1 admin and ' . count($propfirms) . ' propfirm users.');
        $this->command->info('');
        $this->command->info('API Tokens for Propfirms:');
        $this->command->info('=======================');
        
        foreach (User::where('role', 'propfirm')->get() as $propfirm) {
            $this->command->info($propfirm->propfirm_name . ':');
            $this->command->info('Email: ' . $propfirm->email);
            $this->command->info('Token: ' . $propfirm->api_token);
            $this->command->info('');
        }
    }

    /**
     * Create sample payouts for a propfirm.
     */
    private function createSamplePayouts(User $propfirm): void
    {
        $statuses = [0, 2, 3, 4, 5]; // Pending, Confirmed, Released, Proof Uploaded, Final Payout
        
        foreach ($statuses as $status) {
            Payout::create([
                'payout_id' => 'PO-' . strtoupper(substr($propfirm->propfirm_name, 0, 3)) . '-' . rand(1000, 9999),
                'propfirm_id' => $propfirm->id,
                'beneficiary_name' => 'John Doe',
                'beneficiary_email' => 'john@example.com',
                'beneficiary_account_number' => '1234567890',
                'beneficiary_bank' => 'HDFC Bank',
                'ifsc_code' => 'HDFC0001234',
                'payment_type' => 'bank',
                'amount' => rand(1000, 10000),
                'currency' => 'USD',
                'status' => $status,
            ]);
        }

        // Create one UPI payout
        Payout::create([
            'payout_id' => 'PO-' . strtoupper(substr($propfirm->propfirm_name, 0, 3)) . '-' . rand(1000, 9999),
            'propfirm_id' => $propfirm->id,
            'beneficiary_name' => 'Jane Smith',
            'beneficiary_email' => 'jane@example.com',
            'upi_id' => 'jane@upi',
            'payment_type' => 'upi',
            'amount' => rand(500, 5000),
            'currency' => 'USD',
            'status' => 2, // Confirmed
        ]);
    }
}
