<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Number;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'slug'        => $this->slug,
            'price'       => $this->round_price,
            'thumbnail'   => $this->thumbnail,
            'description' => $this->description,
            // relations
            'category'       => new CategoryResource($this->whenLoaded('category')),
            'productImages'  => ProductImageResource::collection($this->whenLoaded('productImages')),
            'variants'       => VariantResource::collection($this->whenLoaded('variants')),
            // 'reviews'        => ReviewResource::collection($this->whenLoaded('reviews')),
        ];
        
        // id
        // category_id
        // name
        // slug
        // price
        // thumbnail
        // description
    }
}
