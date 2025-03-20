<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VariantValueResource extends JsonResource
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
            'id'    => $this->id,
            'value' => $this->value,
            // relations
            'variantAttribute' => new VariantAttributeResource($this->whenLoaded('variantAttribute')),
            'variants'         => VariantResource::collection($this->whenLoaded('variants')),
        ];

        // id
        // variant_attribute_id
        // value
    }
}
