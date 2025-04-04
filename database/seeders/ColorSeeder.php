<?php

namespace Database\Seeders;

use App\Models\Color;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ColorSeeder extends Seeder
{
    public function run()
    {
        $colors = ['Red', 'Green', 'Blue', 'Yellow', 'Black', 'White', 'Orange', 'Purple'];

        foreach ($colors as $color) {
            Color::create(['name' => $color]);
        }
    }
}