<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'id'       => $this->id,
            'name'     => $this->name,
            'email'    => $this->email,
            // 'password' => $this->password,
            'phone'    => $this->phone,
            'address'  => $this->address,
            'avatar'   => $this->avatar,
            'role'     => $this->role,
            'totalAmount' => $this->when($this->relationLoaded('cartItems'),$this->total_amount),
            // relations
            // 'reviews'     => ReviewResource::collection($this->whenLoaded('reviews')),
            'cartItems'   => CartItemResource::collection($this->whenLoaded('cartItems')),
            'orders'      => OrderResource::collection($this->whenLoaded('orders')),
        ];

        // id
        // name
        // email
        // email_verified_at
        // password
        // phone
        // address
        // avatar
        // role
        // remember_token
        
        // Accessor
        // total_amount
    }
}
