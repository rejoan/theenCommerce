<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class GenerateInvoiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3; // Retry the job 3 times if it fails.
    public Order $order;

    /**
     * Create a new job instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Prevent race conditions by refreshing the model and checking status
        $this->order->refresh();

        if ($this->order->invoice_generated_at) {
            return; // Invoice already generated
        }

        $invoiceContent = "Invoice for Order: {$this->order->order_number}\n";
        $invoiceContent .= "Date: " . now()->toFormattedDateString() . "\n";
        $invoiceContent .= "Buyer: {$this->order->buyer->name}\n";
        $invoiceContent .= "Total Amount: {$this->order->total_amount}\n\n";
        $invoiceContent .= "Items:\n";

        foreach ($this->order->items as $item) {
            $invoiceContent .= "- {$item->product->name} (Qty: {$item->quantity}) @ {$item->price}\n";
        }

        $fileName = "invoices/invoice-{$this->order->order_number}.txt";
        Storage::put($fileName, $invoiceContent);

        // Mark the order as invoiced
        $this->order->update(['invoice_generated_at' => now()]);
    }
}
