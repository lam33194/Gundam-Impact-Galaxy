<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
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
            'id'            => $this->id,
            'paymentUrl'    => $this->payment_url,
            'paymentMethod' => $this->payment_method,
            'status'        => $this->status,
            'transactionId' => $this->transaction_id,
            'paidAt'        => $this->paid_at,
            'amount'        => $this->amount,
            'responseCode'  => $this->response_code,     
            // Relation
            'order'         => new OrderResource($this->whenLoaded('order')),
        ];
    }

    // id
    // order_id
    // payment_url
    // payment_method
    // status
    // transaction_id
    // paid_at
    // amount
    // response_code
}
