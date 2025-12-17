<?php

namespace App\Services;

use App\Models\Payout;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailNotificationService
{
    /**
     * Send rejection email to propfirm.
     */
    public function sendRejectedEmail(Payout $payout): void
    {
        try {
            // TODO: Implement email sending
            Log::info('Rejection email sent', [
                'payout_id' => $payout->payout_id,
                'email' => $payout->propfirm->propfirm_email,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send rejection email', [
                'payout_id' => $payout->payout_id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Send confirmation email to propfirm.
     */
    public function sendConfirmedEmail(Payout $payout): void
    {
        try {
            // TODO: Implement email sending
            Log::info('Confirmation email sent', [
                'payout_id' => $payout->payout_id,
                'email' => $payout->propfirm->propfirm_email,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send confirmation email', [
                'payout_id' => $payout->payout_id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Send completion email to propfirm.
     */
    public function sendCompletedEmail(Payout $payout): void
    {
        try {
            // TODO: Implement email sending
            Log::info('Completion email sent', [
                'payout_id' => $payout->payout_id,
                'email' => $payout->propfirm->propfirm_email,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send completion email', [
                'payout_id' => $payout->payout_id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
