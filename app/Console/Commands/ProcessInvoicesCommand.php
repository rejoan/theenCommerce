<?php
/**
 * Developer: Rejoanul Alam | Reviewed: 2025‑10‑16
 */
namespace App\Console\Commands;

use App\Jobs\GenerateInvoiceJob;
use App\Models\Order;
use Illuminate\Console\Command;

class ProcessInvoicesCommand extends Command
{
    protected $signature = 'orders:process-invoices';
    protected $description = 'Finds paid, uninvoiced orders and dispatches jobs to generate invoices.';

    public function handle(): void
    {
        $this->info('Starting to process invoices...');

        // Find orders that are 'paid' and have no invoice yet.
        $ordersToProcess = Order::where('status', 'paid')
            ->whereNull('invoice_generated_at')
            ->get();
            
        if ($ordersToProcess->isEmpty()) {
            $this->info('No orders to process.');
            return;
        }

        $bar = $this->output->createProgressBar($ordersToProcess->count());
        $bar->start();
        
        foreach ($ordersToProcess as $order) {
            GenerateInvoiceJob::dispatch($order);
            $bar->advance();
        }

        $bar->finish();
        $this->info("\nDispatched invoice generation jobs for {$ordersToProcess->count()} orders.");
    }
}