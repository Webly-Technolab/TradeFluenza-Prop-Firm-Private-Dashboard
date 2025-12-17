<?php

namespace App\Http\Controllers\Propfirm;

use App\Http\Controllers\Controller;
use App\Models\Payout;
use App\Services\PayoutService;
use Illuminate\Http\Request;

class PropfirmPayoutController extends Controller
{
    public function __construct(
        private PayoutService $payoutService
    ) {}

    /**
     * Show pending payouts for logged-in propfirm.
     */
    public function index()
    {
        $payouts = Payout::where('propfirm_id', auth()->id())
            ->where('status', 0) // Pending only
            ->latest()
            ->paginate(20, ['*'], 'active_page');

        $history_payouts = Payout::where('propfirm_id', auth()->id())
            ->where('status', '>', 0) // All other statuses
            ->latest()
            ->paginate(20, ['*'], 'history_page');

        return view('propfirm.payouts.index', compact('payouts', 'history_payouts'));
    }

    /**
     * Confirm payout (status 0 → 2).
     */
    public function confirm(Payout $payout)
    {
        if ($payout->propfirm_id !== auth()->id()) {
            abort(403);
        }

        try {
            $this->payoutService->confirmPayout($payout);

            return redirect()->back()->with('success', 'Payout confirmed successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to confirm payout: ' . $e->getMessage());
        }
    }

    /**
     * Reject payout (status 0 → 1).
     */
    public function reject(Request $request, Payout $payout)
    {
        if ($payout->propfirm_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        try {
            $this->payoutService->rejectPayout($payout, $request->rejection_reason);

            return redirect()->back()->with('success', 'Payout rejected');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to reject payout: ' . $e->getMessage());
        }
    }
}
