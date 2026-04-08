<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class MallCartResource extends ApiResource
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
            'goods_id' => $this->goods_id,
            'goods_sku_id' => $this->goods_sku_id,
            'goods_number' => $this->goods_number,
            'goods' => $this->whenLoaded('goods', function () {
                return [
                    'id' => $this->goods->id,
                    'goods_name' => $this->goods->goods_name,
                    'main_img' => $this->publicFileUrl($this->goods->main_img),
                    'is_sale' => $this->goods->is_sale,
                ];
            }),
            'sku' => $this->whenLoaded('sku', function () {
                return [
                    'id' => $this->sku->id,
                    'spec' => $this->sku->spec,
                    'spec_text' => $this->sku->spec_text,
                    'price' => $this->sku->price,
                    'sku_img' => $this->publicFileUrl($this->sku->sku_img),
                    'stock' => $this->sku->stock,
                ];
            }),
        ];
    }
}
