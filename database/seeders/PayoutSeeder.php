<?php

namespace Database\Seeders;

use App\Models\Payout;
use Illuminate\Database\Seeder;

class PayoutSeeder extends Seeder
{
    public function run(): void
    {
        // Get propfirm users (IDs 2 and 3)
        $propfirm1Id = 2;
        $propfirm2Id = 3;

        // Create payouts with different statuses
        $payouts = [
            // Status 0 - Pending
            [
                'payout_id' => 'PO-' . time() . '-001',
                'propfirm_id' => $propfirm1Id,
                'beneficiary_name' => 'John Doe',
                'beneficiary_email' => 'john@example.com',
                'upi_id' => 'john@upi',
                'payment_type' => 'upi',
                'amount' => 1000.00,
                'currency' => 'USD',
                'status' => 0,
            ],
            // Status 2 - Confirmed
            [
                'payout_id' => 'PO-' . time() . '-002',
                'propfirm_id' => $propfirm1Id,
                'beneficiary_name' => 'Jane Smith',
                'beneficiary_email' => 'jane@example.com',
                'beneficiary_account_number' => '1234567890',
                'beneficiary_bank' => 'HDFC Bank',
                'ifsc_code' => 'HDFC0001234',
                'payment_type' => 'bank',
                'amount' => 2500.00,
                'currency' => 'USD',
                'status' => 2,
            ],
            // Status 3 - Released
            [
                'payout_id' => 'PO-' . time() . '-003',
                'propfirm_id' => $propfirm2Id,
                'beneficiary_name' => 'Bob Johnson',
                'beneficiary_email' => 'bob@example.com',
                'upi_id' => 'bob@paytm',
                'payment_type' => 'upi',
                'amount' => 1500.00,
                'currency' => 'USD',
                'status' => 3,
            ],
        ];

        foreach ($payouts as $payout) {
            Payout::create($payout);
        }
    }
}
