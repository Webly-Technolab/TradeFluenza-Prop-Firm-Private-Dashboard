<?php

use App\Http\Controllers\Api\WebhookController;
use Illuminate\Support\Facades\Route;

// Webhook endpoint for propfirms to send payout requests
Route::post('/webhook/payouts', [WebhookController::class, 'receivePayout'])
    ->name('webhook.payouts');
