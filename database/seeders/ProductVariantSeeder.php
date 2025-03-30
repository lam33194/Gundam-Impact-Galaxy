<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductVariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('product_variants')->insert([
            [
                'product_id' => 1,
                'product_size_id' => 1,
                'product_color_id' => 1,
                'quantity' => 50,
                'image' => '/images/rx-78-2-white-144.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'product_id' => 1,
                'product_size_id' => 2,
                'product_color_id' => 2,
                'quantity' => 30,
                'image' => '/images/rx-78-2-red-100.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'product_id' => 2,
                'product_size_id' => 2,
                'product_color_id' => 4,
                'quantity' => 20,
                'image' => '/images/wing-zero-silver-100.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'product_id' => 2,
                'product_size_id' => 3,
                'product_color_id' => 3,
                'quantity' => 15,
                'image' => '/images/wing-zero-blue-60.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
