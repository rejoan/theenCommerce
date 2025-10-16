<?php

use App\Http\Controllers\Api\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Order Placement Endpoint
    Route::post('/orders', [OrderController::class, 'store']);
    
    // Example of a protected route using the policy
    // Route::get('/orders/{order}', function (App\Models\Order $order) {
    //     $this->authorize('view', $order);
    //     return $order->load('items');
    // });
});