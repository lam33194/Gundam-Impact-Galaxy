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
            // 'id'          => $this->id,
            // 'category_id' => $this->category_id,
            'name'        => $this->name,
            'slug'        => $this->slug,
            'price'       => number_format($this->price, 0, ',', '.') . ' VND',
            'thumbnail'   => $this->thumbnail,
            'description' => $this->description,

            'category'       => new CategoryResource($this->whenLoaded('category')),
            // 'variants'       => VariantResource::collection($this->whenLoaded('variants')),
            // 'product_images' => ProductImagesResource::collection($this->whenLoaded('product_images')),
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
