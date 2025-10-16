<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendOrderConfirmationListener implements ShouldQueue
{
    use InteractsWithQueue;

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
        // Simulate sending an email. In a real application, this would use Mail::to()->send().
        $buyerEmail = $event->order->buyer->email;
        Log::info("Simulating order confirmation email to {$buyerEmail} for order #{$event->order->order_number}.");
    
    }
}
