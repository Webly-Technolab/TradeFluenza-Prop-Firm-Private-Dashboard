<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login
     */
    public function login(Request $request)
    {
        // 1️⃣ Validate credentials
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->boolean('remember');

        // 2️⃣ Attempt login
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // 3️⃣ Redirect STRICTLY by role
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }

            if ($user->isPropfirm()) {
                return redirect()->route('propfirm.dashboard');
            }

            // 4️⃣ Safety fallback (invalid role)
            Auth::logout();
            abort(403, 'Unauthorized role');
        }

        // 5️⃣ Invalid credentials
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
