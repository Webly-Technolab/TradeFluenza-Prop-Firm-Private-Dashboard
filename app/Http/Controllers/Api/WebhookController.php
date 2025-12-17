<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\PayoutService;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function __construct(
        private PayoutService $payoutService
    ) {}

    /**
     * Receive payout webhook from propfirm website.
     */
    public function receivePayout(Request $request)
    {
        $request->validate([
            'api_key' => 'required|string',
            'beneficiary_name' => 'required|string',
            'beneficiary_email' => 'required|email',
            'amount' => 'required|numeric|min:0',
            'payment_type' => 'required|in:upi,bank',
            'upi_id' => 'required_if:payment_type,upi',
            'beneficiary_account_number' => 'required_if:payment_type,bank',
            'beneficiary_bank' => 'required_if:payment_type,bank',
            'ifsc_code' => 'required_if:payment_type,bank',
        ]);

        // Find propfirm by API key
        $propfirm = User::where('api_token', $request->api_key)
            ->where('role', 'propfirm')
            ->first();

        if (!$propfirm) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid API key',
            ], 401);
        }

        try {
            $payout = $this->payoutService->createPayout($propfirm, $request->all());

            return response()->json([
                'success' => true,
                'message' => 'Payout created successfully',
                'data' => [
                    'payout_id' => $payout->payout_id,
                    'status' => $payout->status,
                    'status_label' => $payout->status_label,
                ],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create payout: ' . $e->getMessage(),
            ], 500);
        }
    }
}
