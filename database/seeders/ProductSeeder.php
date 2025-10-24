<?php
/**
 * Developer: Rejoanul Alam | Reviewed: 2025‑10‑16
 */
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $seller = User::where('role', 'seller')->firstOrFail();
        DB::table('products')->insert([
            [
            'name' => fake()->words(3, true),
            'price' => rand(100, 300),
            'stock_quantity' => rand(10, 30),
            'user_id' => $seller->id,
            'created_at' => Carbon::now()
            ],
            [
            'name' => fake()->words(3, true),
            'price' => rand(100, 300),
            'stock_quantity' => rand(10, 30),
            'user_id' => $seller->id,
            'created_at' => Carbon::now()
            ]
        ]);
    }
}
