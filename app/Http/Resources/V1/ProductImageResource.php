<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductImageResource extends JsonResource
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
            'variant_id' => $this->variant_id,
            'image_url'  => $this->image_url,
            'is_primary' => $this->is_primary,

            'relations'  => [
                'product'  => new ProductResource($this->whenLoaded('product')),
                'variant'  => new VariantResource($this->whenLoaded('variant'))            
            ]
        ];
        // product_id
        // variant_id
        // image_url
        // is_primary
    }
}
