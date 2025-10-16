<?php
/**
 * Developer: Rejoanul Alam | Reviewed: 2025‑10‑16
 */
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        User::factory(1)->create([
            'name' => 'Rejoanul Buyer',
            'email' => 'buyer_rejoan@example.com',
            'role' => 'buyer',
            'password' => Hash::make('123456'),
        ]);
        User::factory(1)->create([
            'name' => 'Rejoanul Seller',
            'email' => 'seller_rejoan@example.com',
            'role' => 'seller',
            'password' => Hash::make('123456'),
        ]);
    }
}
