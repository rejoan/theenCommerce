<?php
/**
 * Developer: Rejoanul Alam | Reviewed: 2025‑10‑17
 */
namespace App\Providers;

use App\Events\OrderPlaced;
use App\Listeners\AuditTrailListener;
use App\Listeners\SendOrderConfirmationListener;
use App\Listeners\UpdateSellerBalanceListener;
use App\Models\Order;
use App\Observers\OrderObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        OrderPlaced::class => [
            UpdateSellerBalanceListener::class,
            SendOrderConfirmationListener::class,
            AuditTrailListener::class,
        ],
    ];
    
    protected $observers = [
        Order::class => [OrderObserver::class],
    ];

    public function boot(): void
    {
        Schema::defaultStringLength(191);
    }

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}