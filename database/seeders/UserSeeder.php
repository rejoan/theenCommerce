<?php
/**
 * Developer: Rejoanul Alam | Reviewed: 2025‑10‑16
 */
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\Product;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        User::truncate();
        Product::truncate();
        Schema::enableForeignKeyConstraints();
        
        User::factory(1)->create([
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'role' => 'buyer',
            'balance' => '1000.0',
            'password' => Hash::make('123456'),
        ]);
        User::factory(1)->create([
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'role' => 'seller',
            'balance' => '1000.0',
            'password' => Hash::make('123456'),
        ]);
    }
}
