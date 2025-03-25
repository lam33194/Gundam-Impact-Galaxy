<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'id'               => $this->id,
            'orderCode'        => $this->order_code,
            'totalAmount'      => $this->total_amount,
            'status'           => $this->status,
            'shippingAddress'  => $this->shipping_address,
            'shippingFee'      => $this->shipping_fee,
            'note'             => $this->note,
            // Relations
            'user'             => new UserResource($this->whenLoaded('user')),
            'orderItems'       => OrderItemResource::collection($this->whenLoaded('orderItems')),
            'payment'          => new PaymentResource($this->whenLoaded('payment')),
        ];

        // id
        // user_id
        // order_code
        // total_amount
        // status
        // shipping_address
        // shipping_fee
        // note
    }
}
