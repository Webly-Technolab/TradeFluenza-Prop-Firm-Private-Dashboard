<?php

namespace App\Services;

use App\Models\Payout;
use App\Models\PaymentProof;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PayoutService
{
    public function __construct(
        private EmailNotificationService $emailService
    ) {}

    /**
     * Create payout from webhook (status 0).
     */
    public function createPayout(User $propfirm, array $data): Payout
    {
        return DB::transaction(function () use ($propfirm, $data) {
            $payout = Payout::create([
                'payout_id' => 'PO-' . time() . '-' . uniqid(),
                'propfirm_id' => $propfirm->id,
                'beneficiary_name' => $data['beneficiary_name'],
                'beneficiary_email' => $data['beneficiary_email'],
                'beneficiary_account_number' => $data['beneficiary_account_number'] ?? null,
                'beneficiary_bank' => $data['beneficiary_bank'] ?? null,
                'ifsc_code' => $data['ifsc_code'] ?? null,
                'upi_id' => $data['upi_id'] ?? null,
                'payment_type' => $data['payment_type'] ?? 'bank',
                'amount' => $data['amount'],
                'currency' => $data['currency'] ?? 'USD',
                'status' => 0, // Pending
            ]);

            Log::info('Payout created', ['payout_id' => $payout->payout_id]);
            
            return $payout;
        });
    }

    /**
     * Reject payout (status 0 → 1).
     */
    public function rejectPayout(Payout $payout, string $reason): bool
    {
        $success = $payout->reject($reason);
        
        if ($success) {
            $this->emailService->sendRejectedEmail($payout);
            Log::info('Payout rejected', ['payout_id' => $payout->payout_id]);
        }
        
        return $success;
    }

    /**
     * Confirm payout (status 0 → 2).
     */
    public function confirmPayout(Payout $payout): bool
    {
        $success = $payout->confirm();
        
        if ($success) {
            $this->emailService->sendConfirmedEmail($payout);
            Log::info('Payout confirmed', ['payout_id' => $payout->payout_id]);
        }
        
        return $success;
    }

    /**
     * Release payout (status 2 → 3).
     */
    public function releasePayout(Payout $payout): bool
    {
        $success = $payout->release();
        
        if ($success) {
            Log::info('Payout released', ['payout_id' => $payout->payout_id]);
        }
        
        return $success;
    }

    /**
     * Upload payment proofs (status 3 → 4).
     */
    public function uploadProofs(Payout $payout, array $proofPaths): bool
    {
        return DB::transaction(function () use ($payout, $proofPaths) {
            foreach ($proofPaths as $path) {
                PaymentProof::create([
                    'payout_id' => $payout->id,
                    'proof_path' => $path,
                ]);
            }
            
            $success = $payout->markProofUploaded();
            
            if ($success) {
                Log::info('Payment proofs uploaded', [
                    'payout_id' => $payout->payout_id,
                    'count' => count($proofPaths),
                ]);
            }
            
            return $success;
        });
    }

    /**
     * Mark final payout (status 4 → 5).
     */
    public function finalPayout(Payout $payout): bool
    {
        $success = $payout->markFinalPayout();
        
        if ($success) {
            Log::info('Final payout marked', ['payout_id' => $payout->payout_id]);
        }
        
        return $success;
    }

    /**
     * Complete payout (status 5 → 6).
     */
    public function completePayout(Payout $payout): bool
    {
        $success = $payout->complete();
        
        if ($success) {
            $this->emailService->sendCompletedEmail($payout);
            Log::info('Payout completed', ['payout_id' => $payout->payout_id]);
        }
        
        return $success;
    }

    /**
     * Generate API key for propfirm.
     */
    public function generateApiKey(User $propfirm): string
    {
        if ($propfirm->api_token) {
            return $propfirm->api_token;
        }

        $timestamp = time();
        $random = bin2hex(random_bytes(16));
        $hostname = gethostname();
        $payoutId = 'INIT';
        
        $keyPattern = implode('_', [
            $payoutId,
            $timestamp,
            $random,
            $hostname,
            $propfirm->id
        ]);
        
        $apiKey = md5($keyPattern);
        
        $propfirm->update(['api_token' => $apiKey]);
        
        return $apiKey;
    }
}
