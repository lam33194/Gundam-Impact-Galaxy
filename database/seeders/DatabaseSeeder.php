<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,        // +1 user (admin)
            CategorySeeder::class,
            ProductSizeSeeder::class,
            ProductColorSeeder::class,
            ProductSeeder::class,
            ProductVariantSeeder::class,
            // ColorSeeder::class,
        ]);
    }
}
