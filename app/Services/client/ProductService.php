<?php

namespace App\Services\Client;

use App\Models\Product;

class ProductService
{
    // public function getTopService(string $limit)
    // {
    //     return Product::with([
    //         'category.products',
    //         'tags',
    //         'galleries',
    //         'variants.product',
    //         'variants.size',
    //         'variants.color'
    //     ])
    //         ->where([['is_hot_deal', 1], ['is_active', 1]])
    //         ->latest('id')
    //         ->limit($limit)
    //         ->get();
    // }

    public function getAllService()
    {
        return Product::with([
            'category.products',
            'tags',
            'galleries',
            'variants.product',
            'variants.size',
            'variants.color'
        ])
            ->where('is_active', 1)
            ->latest('id')
            ->get();
    }

    public function getDetailService(string $slug)
    {
        return Product::with([
            'category.products',
            'tags',
            'galleries',
            'variants.product',
            'variants.size',
            'variants.color'
        ])
            ->where('slug', $slug)
            ->firstOrFail();
    }
}
