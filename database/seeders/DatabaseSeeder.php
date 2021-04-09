<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::query()->create([
             'name' => 'Surya',
             'email' => 'surya@email.com',
             'password' => Hash::make('12345678'),
         ]);

        $this->call(ProductSeeder::class);
    }
}
