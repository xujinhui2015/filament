<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class MallGoodsResource extends ApiResource
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
            'goods_sn' => $this->goods_sn,
            'goods_name' => $this->goods_name,
            'goods_category_id' => $this->goods_category_id,
            'subtitle' => $this->whenNotNull($this->subtitle),
            'main_img' => $this->publicFileUrl($this->main_img),
            'images' => $this->when(
                is_array($this->images),
                fn (): array => $this->publicFileUrls($this->images),
            ),
            'content' => $this->whenNotNull($this->content),
            'is_sale' => $this->whenNotNull($this->is_sale),
            'sku_min_price' => $this->whenNotNull($this->sku_min_price),
            'sku' => $this->whenLoaded('sku', function () {
                return $this->sku->map(function ($sku) {
                    return [
                        'id' => $sku->id,
                        'goods_id' => $sku->goods_id,
                        'spec' => $sku->spec,
                        'spec_text' => $sku->spec_text,
                        'price' => $sku->price,
                        'sku_img' => $this->publicFileUrl($sku->sku_img),
                        'stock' => $sku->stock,
                    ];
                })->all();
            }),
            'attr' => $this->whenLoaded('attr', function () {
                return $this->attr->map(function ($attr) {
                    return [
                        'id' => $attr->id,
                        'goods_id' => $attr->goods_id,
                        'attr_name' => $attr->attr_name,
                        'attr_value' => $attr->attrValue->map(function ($attrValue) {
                            return [
                                'id' => $attrValue->id,
                                'goods_attr_id' => $attrValue->goods_attr_id,
                                'attr_value_name' => $attrValue->attr_value_name,
                            ];
                        })->all(),
                    ];
                })->all();
            }),
        ];
    }
}
