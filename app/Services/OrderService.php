<?php
/**
 * Developer: Rejoanul Alam | Reviewed: 2025‑10‑16
 */
namespace App\Services;

use App\Events\OrderPlaced;
use App\Models\Order;
use App\Models\User;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class OrderService
{
    protected ProductRepository $productRepository;
    protected OrderRepository $orderRepository;

    public function __construct(ProductRepository $productRepository, OrderRepository $orderRepository)
    {
        $this->productRepository = $productRepository;
        $this->orderRepository = $orderRepository;
    }

    /**
     * Create an order within a database transaction.
     *
     * @throws \Throwable
     */
    public function createOrder(array $data): Order
    {
        // Wrap the entire process in a database transaction.
        // If any step fails, all database changes will be rolled back.
        return DB::transaction(function () use ($data) {
            $buyer = User::findOrFail($data['buyer_id']);
            $productIds = collect($data['items'])->pluck('product_id')->all();

            // Step 1: Validate stock and compute totals.
            // Lock products to prevent race conditions.
            $products = $this->productRepository->findManyForUpdate($productIds);
            
            $totalAmount = 0;
            $orderItemsData = [];

            foreach ($data['items'] as $item) {
                $product = $products->find($item['product_id']);

                if (!$product || $product->stock_quantity < $item['quantity']) {
                    throw new InvalidArgumentException("Product ID {$item['product_id']} is unavailable or out of stock.");
                }

                $totalAmount += $product->price * $item['quantity'];
                $orderItemsData[] = [
                    'product_id' => $product->id,
                    'seller_id' => $product->user_id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ];
            }

            // Step 2: Create Order and OrderItems.
            $order = $this->orderRepository->create($buyer, $orderItemsData, $totalAmount);

            // Step 3: Reduce stock.
            foreach ($data['items'] as $item) {
                $this->productRepository->decrementStock($item['product_id'], $item['quantity']);
            }

            // Step 4: Fire the OrderPlaced event.
            // This happens after the transaction is successfully committed.
            OrderPlaced::dispatch($order);
            
            return $order;
        });
    }
}