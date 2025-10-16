<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\User;

class OrderRepository
{
    /**
     * Create a new order and its associated items.
     */
    public function create(User $buyer, array $items, float $totalAmount): Order
    {
        $order = $buyer->orders()->create([
            'total_amount' => $totalAmount,
            'status' => 'pending',
        ]);

        $order->items()->createMany($items);

        return $order;
    }
}