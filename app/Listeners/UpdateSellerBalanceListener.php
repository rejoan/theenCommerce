<?php
/**
 * Developer: Rejoanul Alam | Reviewed: 2025‑10‑17
 */
namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateSellerBalanceListener implements ShouldQueue
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
        $sellerTotals = $event->order->items()
            ->select('seller_id', DB::raw('SUM(price * quantity) as total'))
            ->groupBy('seller_id')
            ->get();

        foreach ($sellerTotals as $sellerTotal) {
            try {
                $seller = User::find($sellerTotal->seller_id);
                if ($seller) {
                    $seller->increment('balance', $sellerTotal->total);
                    Log::info("Updated balance for seller {$seller->id} by {$sellerTotal->total}.");
                }
            } catch (\Exception $e) {
                Log::error("Failed to update balance for seller {$sellerTotal->seller_id}: " . $e->getMessage());
                // The job will be retried automatically by the queue worker.
                $this->release(30); // Optionally release back to queue with delay
            }
        }
    }
}
