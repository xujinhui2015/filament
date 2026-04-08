<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class MallOrderDetailResource extends ApiResource
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
            'goods_id' => $this->goods_id,
            'goods_sku_id' => $this->goods_sku_id,
            'goods_name' => $this->goods_name,
            'goods_spec' => $this->goods_spec,
            'goods_image' => $this->publicFileUrl($this->goods_image),
            'goods_price' => $this->goods_price,
            'goods_number' => $this->goods_number,
        ];
    }
}
