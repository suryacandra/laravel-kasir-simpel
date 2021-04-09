<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::query()->create([
            'name' => 'Nvidia GeForce RTX 3090',
            'price' => 50000000,
            'quantity' => 3,
            'image_url' => 'products/3090.png',
        ]);
        Product::query()->create([
            'name' => 'Nvidia GeForce RTX 3080',
            'price' => 50000000,
            'quantity' => 5,
            'image_url' => 'products/3080.png',
        ]);
        Product::query()->create([
            'name' => 'Nvidia GeForce RTX 3070',
            'price' => 50000000,
            'quantity' => 10,
            'image_url' => 'products/3070.png',
        ]);
    }
}
