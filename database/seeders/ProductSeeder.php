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
            'name' => 'Sepatu A',
            'price' => 1000000,
            'quantity' => 3,
            'image_url' => 'products/a.jpg',
        ]);
        Product::query()->create([
            'name' => 'Sepatu B',
            'price' => 500000,
            'quantity' => 5,
            'image_url' => 'products/b.jpg',
        ]);
        Product::query()->create([
            'name' => 'Sepatu C',
            'price' => 300000,
            'quantity' => 10,
            'image_url' =>  'products/c.jpg',
        ]);
    }
}
