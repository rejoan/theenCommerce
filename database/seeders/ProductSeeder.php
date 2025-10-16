<?php
/**
 * Developer: Rejoanul Alam | Reviewed: 2025‑10‑16
 */
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $seller = User::where('role', 'seller')->firstOrFail();
        Product::factory(5)->create([
            'name' => Str::random(10),
            'prices' => rand(100, 300),
            'stock_quantity' => rand(1, 30),
            'user_id' => $seller->id,
        ]);
    }
}
