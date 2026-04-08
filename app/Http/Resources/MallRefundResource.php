<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class MallRefundResource extends ApiResource
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
            'order_id' => $this->order_id,
            'refund_order_no' => $this->refund_order_no,
            'refund_type' => $this->enumValue($this->refund_type),
            'refund_status' => $this->enumValue($this->refund_status),
            'refund_money' => $this->refund_money,
            'phone' => $this->phone,
            'refund_reason' => $this->whenNotNull($this->refund_reason),
            'buyer_message' => $this->whenNotNull($this->buyer_message),
            'buyer_images' => $this->when(
                is_array($this->buyer_images),
                fn (): array => $this->publicFileUrls($this->buyer_images),
            ),
            'seller_message' => $this->whenNotNull($this->seller_message),
            'created_at' => $this->whenNotNull($this->created_at),
            'detail' => $this->whenLoaded('detail', function () use ($request) {
                return $this->detail->map(function ($detail) use ($request) {
                    return [
                        'id' => $detail->id,
                        'order_refund_id' => $detail->order_refund_id,
                        'order_detail_id' => $detail->order_detail_id,
                        'goods_id' => $detail->goods_id,
                        'goods_sku_id' => $detail->goods_sku_id,
                        'refund_number' => $detail->refund_number,
                        'order_detail' => $detail->relationLoaded('orderDetail') && $detail->orderDetail
                            ? MallOrderDetailResource::make($detail->orderDetail)->toArray($request)
                            : null,
                    ];
                })->all();
            }),
        ];
    }
}
