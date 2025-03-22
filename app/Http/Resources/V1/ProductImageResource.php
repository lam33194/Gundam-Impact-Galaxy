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
            'id'        => $this->id,
            'productId' => $this->product_id,
            'variantId' => $this->variant_id,
            'imageUrl'  => $this->image_url,
            'isPrimary' => $this->is_primary,           
        ];

        // id
        // product_id
        // variant_id
        // image_url
        // is_primary
    }
}
