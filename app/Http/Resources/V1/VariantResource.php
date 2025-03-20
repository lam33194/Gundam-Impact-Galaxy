<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VariantResource extends JsonResource
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
            'variantName'  => $this->variant_name,
            'sku'          => $this->sku,
            'stock'        => $this->stock,
            'extraPrice'   => $this->extra_price,
            // relations
            'product'       => new ProductResource($this->whenLoaded('product')),
            'productImages' => ProductImageResource::collection($this->whenLoaded('productImages')),
            'variantValues' => VariantValueResource::collection($this->whenLoaded('variantValues')),
        ];

        // id
        // product_id
        // variant_name
        // sku
        // stock
        // extra_price
    }
}
