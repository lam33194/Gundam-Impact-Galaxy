<?php

namespace App\Services\Client;

use App\Models\CartItem;
use App\Models\ProductVariant;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Auth;

class CartService
{
    use ApiResponseTrait;
    public function indexService()
    {
        return CartItem::with([
            'productVariant.product',
            'productVariant.color',
            'productVariant.size',
            'user'
        ])
            ->where('user_id', request()->user()->id)
            ->latest('id')
            ->paginate(10);
    }

    public function storeService(array $data)
    {
        $userId = request()->user()->id;

        $ProductVariant = ProductVariant::findOrFail($data['product_variant_id']);

        $cartItem = CartItem::where([
            'user_id' => $userId,
            'product_variant_id' => $data['product_variant_id']
        ])->first();

        $totalQuantity = $cartItem ? ($cartItem->quantity + $data['quantity']) : $data['quantity'];

        if ($totalQuantity > $ProductVariant->quantity) {
            throw new \Exception('Không còn đủ số lượng !!!', 500);
        }

        if ($cartItem) {
            $cartItem->increment('quantity', $data['quantity']);
            return $cartItem->refresh();
        }


        return CartItem::create([
            'user_id' => $userId,
            'product_variant_id' => $data['product_variant_id'],
            'quantity' => $data['quantity']
        ]);
    }

    public function updateService(array $data, string $id)
    {
        $userId = request()->user()->id;

        $ProductVariant = ProductVariant::findOrFail($id);

        $cartItem = CartItem::where([
            'user_id' => $userId,
            'product_variant_id' => $id
        ])->first();

        if ($data['quantity'] > $ProductVariant->quantity) {
            throw new \Exception('Không còn đủ số lượng !!!', 500);
        }

        return $cartItem->update($data);
    }
    public function destroyService(string $id)
    {
        $userId = request()->user()->id;

        if ((int) $id === 0) {
            return CartItem::where('user_id', $userId)->delete();
        }

        $cartItem = CartItem::where([
            'user_id' => $userId,
            'product_variant_id' => $id
        ])->firstOrFail();

        return $cartItem->delete();
    }
}
