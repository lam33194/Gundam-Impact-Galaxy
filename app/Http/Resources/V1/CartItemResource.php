<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);

        return [
            'id'           => $this->id,
            'productName'  => $this->product_name,
            'productPrice' => $this->product_price,
            'variantName'  => $this->variant_name,
            'sku'          => $this->sku,
            'extraPrice'   => $this->round_extra_price,
            'attributes'   => $this->attributes,
            'quantity'     => $this->quantity,
            // relations
            'user'         => new UserResource($this->whenLoaded('user')),
            'product'      => new ProductResource($this->whenLoaded('product')),
            'variant'      => new VariantResource($this->whenLoaded('variant')),
        ];

        // id
        // user_id 
        // product_id 
        // variant_id (nullable) 
        // product_name
        // product_price
        // variant_name
        // sku
        // extra_price
        // attributes
        // quantity
    }
}
