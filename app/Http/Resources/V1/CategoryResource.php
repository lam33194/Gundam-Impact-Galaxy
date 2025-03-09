<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'name'      => $this->name,
            'slug'      => $this->slug,
            
            'children'  => CategoryResource::collection($this->whenLoaded('children')),
            'parent'    => new CategoryResource($this->whenLoaded('parent')),
            // 'products'  => ProductResource::collection($this->whenLoaded('products')),
        ];
    }
    
    // name
    // slug
    // parent_id
}
