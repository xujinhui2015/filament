<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class MallOrderResource extends ApiResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'customer_id' => $this->whenNotNull($this->customer_id),
            'order_no' => $this->order_no,
            'order_status' => $this->enumValue($this->order_status),
            'order_money' => $this->order_money,
            'order_fact_money' => $this->order_fact_money,
            'order_source' => $this->whenNotNull($this->enumValue($this->order_source)),
            'payment' => $this->whenNotNull($this->enumValue($this->payment)),
            'name' => $this->whenNotNull($this->name),
            'phone' => $this->whenNotNull($this->phone),
            'province' => $this->whenNotNull($this->province),
            'city' => $this->whenNotNull($this->city),
            'district' => $this->whenNotNull($this->district),
            'address' => $this->whenNotNull($this->address),
            'last_pay_time' => $this->whenNotNull($this->last_pay_time),
            'buyer_remark' => $this->whenNotNull($this->buyer_remark),
            'seller_message' => $this->whenNotNull($this->seller_message),
            'created_at' => $this->whenNotNull($this->created_at),
            'detail' => $this->whenLoaded(
                'detail',
                fn () => MallOrderDetailResource::collection($this->detail)->resolve()
            ),
        ];
    }
}
