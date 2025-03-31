<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name' => 'Gundam Models',
            'slug' => 'gundam-models',
            'description' => 'Mô hình Gundam lắp ráp',
        ]);
        
        Category::create([
            'name' => 'Gundam Accessories',
            'slug' => 'gundam-accessories',
            'description' => 'Phụ kiện cho mô hình Gundam',
        ]);

        Category::create([
            'name' => 'Gundam Model Kits',
            'slug' => 'gundam-model-kits',
            'description' => 'Model kits của Gundam',
        ]);
    }
}
