<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payout;
use App\Services\PayoutService;
use Illuminate\Http\Request;

class TradefluenzaPayoutController extends Controller
{
    public function __construct(
        private PayoutService $payoutService,
        private \App\Models\User $userModel
    ) {}

    /**
     * Show confirmed/released/proof uploaded payouts.
     */
    public function index(Request $request)
    {
        $query = Payout::with(['propfirm', 'paymentProofs']);
        $historyQuery = Payout::with(['propfirm', 'paymentProofs']);
        $statsQuery = Payout::query();

        // Apply Filters
        if ($request->filled('propfirm_id')) {
            $query->where('propfirm_id', $request->propfirm_id);
            $historyQuery->where('propfirm_id', $request->propfirm_id);
            $statsQuery->where('propfirm_id', $request->propfirm_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
            $historyQuery->whereDate('created_at', '>=', $request->date_from);
            $statsQuery->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
            $historyQuery->whereDate('created_at', '<=', $request->date_to);
            $statsQuery->whereDate('created_at', '<=', $request->date_to);
        }

        $payouts = $query->clone()
            ->whereIn('status', [2]) // Confirmed
            ->latest()
            ->paginate(20, ['*'], 'active_page');

        $history_payouts = $historyQuery->clone()
            ->whereIn('status', [1, 3, 4, 5, 6]) // All others
            ->latest()
            ->paginate(20, ['*'], 'history_page');

        // Statistics
        $stats = [
            'request' => [
                'count' => (clone $statsQuery)->where('status', 2)->count(),
                'amount' => (clone $statsQuery)->where('status', 2)->sum('amount'),
            ],
            'release' => [
                'count' => (clone $statsQuery)->whereIn('status', [3, 4, 5])->count(),
                'amount' => (clone $statsQuery)->whereIn('status', [3, 4, 5])->sum('amount'),
            ],
            'complete' => [
                'count' => (clone $statsQuery)->where('status', 6)->count(),
                'amount' => (clone $statsQuery)->where('status', 6)->sum('amount'),
            ],
            'total' => [
                'count' => (clone $statsQuery)->count(),
                'amount' => (clone $statsQuery)->sum('amount'),
            ],
        ];

        // Get all propfirms for filter
        $propfirms = $this->userModel->propfirm()->orderBy('propfirm_name')->get();

        return view('admin.tradefluenza.payouts.index', compact('payouts', 'history_payouts', 'stats', 'propfirms'));
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
