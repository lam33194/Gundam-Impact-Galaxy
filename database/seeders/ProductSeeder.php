<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'id' => 1,
                'category_id' => 1,
                'name' => 'Gundam RX-78-2',
                'slug' => 'gundam-rx-78-2',
                'sku' => 'RX782001',
                'thumb_image' => '/images/rx-78-2-thumb.jpg',
                'price_regular' => 1500.00,
                'price_sale' => 1200.00,
                'description' => 'Mô hình Gundam RX-78-2 phiên bản cổ điển',
                'content' => 'Chi tiết về Gundam RX-78-2, nhân vật chính trong Mobile Suit Gundam.',
                'views' => 100,
                'is_active' => true,
                'is_hot_deal' => true,
                'is_good_deal' => false,
                'is_new' => true,
                'is_show_home' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 2,
                'category_id' => 1,
                'name' => 'Gundam Wing Zero',
                'slug' => 'gundam-wing-zero',
                'sku' => 'WGZ002',
                'thumb_image' => '/images/wing-zero-thumb.jpg',
                'price_regular' => 2000.00,
                'price_sale' => 1800.00,
                'description' => 'Mô hình Gundam Wing Zero từ Gundam Wing',
                'content' => 'Gundam Wing Zero với đôi cánh thiên thần đặc trưng.',
                'views' => 50,
                'is_active' => true,
                'is_hot_deal' => false,
                'is_good_deal' => true,
                'is_new' => false,
                'is_show_home' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        DB::table('product_galleries')->insert([
            ['product_id' => 1, 'image' => '/images/rx-78-2-front.jpg', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['product_id' => 1, 'image' => '/images/rx-78-2-back.jpg', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['product_id' => 2, 'image' => '/images/wing-zero-front.jpg', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['product_id' => 2, 'image' => '/images/wing-zero-back.jpg', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
    }
}
