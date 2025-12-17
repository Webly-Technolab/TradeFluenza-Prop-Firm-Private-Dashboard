<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payout;
use App\Services\PayoutService;
use Illuminate\Http\Request;

class TradefluenzaPayoutController extends Controller
{
    public function __construct(
        private PayoutService $payoutService
    ) {}

    /**
     * Show confirmed/released/proof uploaded payouts.
     */
    public function index()
    {
        $payouts = Payout::with(['propfirm', 'paymentProofs'])
            ->whereIn('status', [2, 3, 4, 5]) // Confirmed, Released, Proof Uploaded, Final Payout
            ->latest()
            ->paginate(20, ['*'], 'active_page');

        $history_payouts = Payout::with(['propfirm', 'paymentProofs'])
            ->whereIn('status', [1, 6]) // Rejected, Completed
            ->latest()
            ->paginate(20, ['*'], 'history_page');

        return view('admin.tradefluenza.payouts.index', compact('payouts', 'history_payouts'));
    }

    /**
     * Release payout (status 2 → 3).
     */
    public function release(Payout $payout)
    {
        try {
            $this->payoutService->releasePayout($payout);

            return redirect()->back()->with('success', 'Payout released for payment');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to release payout: ' . $e->getMessage());
        }
    }

    /**
     * Upload payment proofs (status 3 → 4).
     */
    public function uploadProof(Request $request, Payout $payout)
    {
        $request->validate([
            'proofs' => 'required|array|min:1',
            'proofs.*' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        try {
            $proofPaths = [];
            foreach ($request->file('proofs') as $file) {
                $proofPaths[] = $file->store('payment_proofs', 'public');
            }

            $this->payoutService->uploadProofs($payout, $proofPaths);

            return redirect()->back()->with('success', 'Payment proofs uploaded successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to upload proofs: ' . $e->getMessage());
        }
    }

    /**
     * Mark final payout (status 4 → 5).
     */
    public function finalPayout(Payout $payout)
    {
        try {
            $this->payoutService->finalPayout($payout);

            return redirect()->back()->with('success', 'Marked as final payout');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to mark final payout: ' . $e->getMessage());
        }
    }

    /**
     * Complete payout (status 5 → 6).
     */
    public function complete(Payout $payout)
    {
        try {
            $this->payoutService->completePayout($payout);

            return redirect()->back()->with('success', 'Payout completed successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to complete payout: ' . $e->getMessage());
        }
    }
}
