<?php

use App\Http\Controllers\Admin\PropfirmManagementController;
use App\Http\Controllers\Admin\TradefluenzaPayoutController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Propfirm\PropfirmPayoutController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Home – Redirect Based on Role
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('propfirm.dashboard');
    }

    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| PropFirm Routes (🔒 PROTECTED)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'propfirm'])
    ->prefix('propfirm')
    ->name('propfirm.')
    ->group(function () {

        Route::get('/dashboard', [PropfirmPayoutController::class, 'index'])
            ->name('dashboard');

        Route::get('/payouts', [PropfirmPayoutController::class, 'index'])
            ->name('payouts.index');

        Route::post('/payouts/{payout}/confirm', [PropfirmPayoutController::class, 'confirm'])
            ->name('payouts.confirm');

        Route::post('/payouts/{payout}/reject', [PropfirmPayoutController::class, 'reject'])
            ->name('payouts.reject');
    });

/*
|--------------------------------------------------------------------------
| Admin Routes (🔒 PROTECTED)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [TradefluenzaPayoutController::class, 'index'])
            ->name('dashboard');

        // Payout Actions
        Route::post('/payouts/{payout}/release', [TradefluenzaPayoutController::class, 'release'])
            ->name('payouts.release');

        Route::post('/payouts/{payout}/upload-proof', [TradefluenzaPayoutController::class, 'uploadProof'])
            ->name('payouts.upload-proof');

        Route::post('/payouts/{payout}/final-payout', [TradefluenzaPayoutController::class, 'finalPayout'])
            ->name('payouts.final-payout');

        Route::post('/payouts/{payout}/complete', [TradefluenzaPayoutController::class, 'complete'])
            ->name('payouts.complete');

        // PropFirm Management
        Route::resource('propfirms', PropfirmManagementController::class);

        Route::post('/propfirms/{propfirm}/regenerate-api-key',
            [PropfirmManagementController::class, 'regenerateApiKey']
        )->name('propfirms.regenerate-api-key');
    });
