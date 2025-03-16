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
            'name'     => $this->name,
            'email'    => $this->email,
            // 'password' => $this->password,
            'phone'    => $this->phone,
            'address'  => $this->address,
            'avatar'   => $this->avatar,
            'role'     => $this->role,

            'relations' => [
                // 'reviews'    => ReviewResource::collection($this->whenLoaded('reviews')),
                // 'cartItems'  => CartItemResource::collection($this->whenLoaded('cartItems')),
                // 'orders'     => OrderResource::collection($this->whenLoaded('orders')),
            ],

            // 'created_at'     => $this->created_at,
            // 'updated_at'     => $this->updated_at,
            // 'remember_token' => $this->remember_token,
        ];

        // Fields
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
    }
}
