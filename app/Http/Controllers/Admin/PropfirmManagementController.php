<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\PayoutService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PropfirmManagementController extends Controller
{
    public function __construct(
        private PayoutService $payoutService
    ) {}

    /**
     * Show all propfirms.
     */
    public function index()
    {
        $propfirms = User::propfirm()->latest()->paginate(20);
        
        return view('admin.propfirms.index', compact('propfirms'));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        return view('admin.propfirms.create');
    }

    /**
     * Store new propfirm.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'propfirm_name' => 'required|string|max:255',
            'propfirm_email' => 'required|email',
            'propfirm_mobile' => 'nullable|string|max:20',
        ]);

        $propfirm = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'propfirm',
            'propfirm_name' => $request->propfirm_name,
            'propfirm_email' => $request->propfirm_email,
            'propfirm_mobile' => $request->propfirm_mobile,
        ]);

        // Generate API key
        $this->payoutService->generateApiKey($propfirm);

        return redirect()->route('admin.propfirms.index')
            ->with('success', 'Propfirm created successfully');
    }

    /**
     * Show edit form.
     */
    public function edit(User $propfirm)
    {
        if ($propfirm->role !== 'propfirm') {
            abort(404);
        }

        return view('admin.propfirms.edit', compact('propfirm'));
    }

    /**
     * Update propfirm.
     */
    public function update(Request $request, User $propfirm)
    {
        if ($propfirm->role !== 'propfirm') {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $propfirm->id,
            'propfirm_name' => 'required|string|max:255',
            'propfirm_email' => 'required|email',
            'propfirm_mobile' => 'nullable|string|max:20',
        ]);

        $propfirm->update($request->only([
            'name',
            'email',
            'propfirm_name',
            'propfirm_email',
            'propfirm_mobile',
        ]));

        return redirect()->route('admin.propfirms.index')
            ->with('success', 'Propfirm updated successfully');
    }

    /**
     * Delete propfirm.
     */
    public function destroy(User $propfirm)
    {
        if ($propfirm->role !== 'propfirm') {
            abort(404);
        }

        $propfirm->delete();

        return redirect()->route('admin.propfirms.index')
            ->with('success', 'Propfirm deleted successfully');
    }

    /**
     * Regenerate API key.
     */
    public function regenerateApiKey(User $propfirm)
    {
        if ($propfirm->role !== 'propfirm') {
            abort(404);
        }

        $propfirm->update(['api_token' => null]);
        $apiKey = $this->payoutService->generateApiKey($propfirm);

        return redirect()->back()
            ->with('success', 'API key regenerated successfully')
            ->with('api_key', $apiKey);
    }
}
