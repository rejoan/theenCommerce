<?php
/**
 * Developer: Rejoanul Alam | Reviewed: 2025‑10‑16
 */
namespace App\Listeners;

use App\Events\OrderPlaced;
use Illuminate\Support\Facades\File;

class AuditTrailListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderPlaced $event): void
    {
        $logEntry = json_encode([
            'timestamp' => now()->toDateTimeString(),
            'event' => 'OrderPlaced',
            'order_id' => $event->order->id,
            'order_number' => $event->order->order_number,
            'buyer_id' => $event->order->user_id,
            'total_amount' => $event->order->total_amount,
            'items' => $event->order->items->map(function ($item) {
                return [
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ];
            }),
        ], JSON_PRETTY_PRINT);
        
        // Append to storage/logs/orders.log
        File::append(storage_path('logs/orders.log'), $logEntry . PHP_EOL);
    }
}
