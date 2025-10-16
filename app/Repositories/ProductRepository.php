<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Support\Collection;

class ProductRepository
{
    /**
     * Find multiple products by their IDs and lock them for an update.
     * This prevents race conditions during stock checks.
     */
    public function findManyForUpdate(array $productIds): Collection
    {
        return Product::whereIn('id', $productIds)->lockForUpdate()->get();
    }

    /**
     * Decrement the stock for a given product.
     */
    public function decrementStock(int $productId, int $quantity): void
    {
        Product::where('id', $productId)->decrement('stock_quantity', $quantity);
    }
}