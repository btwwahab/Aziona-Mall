<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Example protected route (optional)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Your Stripe webhook route here:
use App\Http\Controllers\WebhookController;

Route::post('/stripe/webhook', [WebhookController::class, 'handleStripeWebhook']);
