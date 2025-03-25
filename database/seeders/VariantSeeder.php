<?php

namespace Database\Seeders;

use App\Models\ProductImage;
use App\Models\Variant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    // 123 random color 
    // 4-S, 5-M, 6-L, 7-XL
    public function run(): void
    {
        $variants = [
            [
                'id' => 1,
                'product_id' => 1,
                'variant_name' => 'Standard Edition',
                'sku' => 'DIVINE-EAGLE-STD-001',
                'stock' => 10,
                'extra_price' => 0,
                'images' => [
                    'https://picsum.photos/1000',
                    'https://picsum.photos/1000',
                ],
                'values' => [
                    1, 4
                ]
            ],
            [
                'id' => 2,
                'product_id' => 1,
                'variant_name' => 'Limited Edition Gold',
                'sku' => 'DIVINE-EAGLE-GOLD-002',
                'stock' => 5,
                'extra_price' => 50000,
                'images' => [
                    'https://picsum.photos/1000',
                    'https://picsum.photos/1000',
                ],
                'values' => [
                    2, 3
                ]
            ],
            [
                'id' => 3,
                'product_id' => 2,
                'variant_name' => 'White Unicorn',
                'sku' => 'UNICORN-RG-WHITE-001',
                'stock' => 20,
                'extra_price' => 0,
                'images' => [
                    'https://picsum.photos/1000',
                ],
                'values' => [
                    2, 4
                ]
            ],
            [
                'id' => 4,
                'product_id' => 2,
                'variant_name' => 'Red Psycho Frame',
                'sku' => 'UNICORN-RG-RED-002',
                'stock' => 15,
                'extra_price' => 100000,
                'images' => [
                    'https://picsum.photos/1000',
                ],
                'values' => [
                    3, 7
                ]
            ],
            [
                'id' => 5,
                'product_id' => 3,
                'variant_name' => 'Black',
                'sku' => 'ACTION-BASE-BLACK-001',
                'stock' => 50,
                'extra_price' => 0,
                'images' => [
                    'https://picsum.photos/1000',
                ],
                'values' => [
                    1, 6
                ]
            ],
            [
                'id' => 6,
                'product_id' => 3,
                'variant_name' => 'Transparent',
                'sku' => 'ACTION-BASE-TRANS-002',
                'stock' => 30,
                'extra_price' => 20000,
                'images' => [
                    'https://picsum.photos/1000',
                ],
                'values' => [
                    3, 5
                ]
            ],
            [
                'id' => 7,
                'product_id' => 4,
                'variant_name' => 'Zaku HG 1/86',
                'sku' => 'SKU-ZKHG186',
                'stock' => 30,
                'extra_price' => 20000,
                'images' => [
                    'https://picsum.photos/1000',
                ],
                'values' => [
                    2, 5
                ]
            ],
            [
                'id' => 8,
                'product_id' => 5,
                'variant_name' => 'Metalbuild Exodia 1/100',
                'sku' => 'DAK-BUH-LMAO',
                'stock' => 30,
                'extra_price' => 20000,
                'images' => [
                    'https://picsum.photos/1000',
                ],
                'values' => [
                    1, 6
                ]
            ],
        ];
        
        foreach ($variants as $variant) {
            $images = $variant['images'] ?? [];
            $values = $variant['values'] ?? [];

            unset($variant['images'], $variant['values']);

            Variant::create($variant);

            foreach ($images as $imgPath) {
                ProductImage::create([
                    'variant_id' => $variant['id'],
                    'product_id' => $variant['product_id'],
                    'image_url'  => $imgPath,
                ]);
            }

            foreach ($values as $value) {
                DB::table('pivot_vv')->insert([
                    'variant_id'       => $variant['id'],
                    'variant_value_id' => $value,
                ]);
            }
        }
    }
}
