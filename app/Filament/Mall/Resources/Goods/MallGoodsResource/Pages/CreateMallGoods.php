<?php

namespace App\Filament\Mall\Resources\Goods\MallGoodsResource\Pages;

use App\Filament\Mall\Resources\Goods\MallGoodsResource;
use App\Models\Mall\MallAttr;
use App\Models\Mall\MallAttrValue;
use App\Models\Mall\MallGoods;
use App\Models\Mall\MallGoodsAttr;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CreateMallGoods extends CreateRecord
{
    protected static string $resource = MallGoodsResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? MallGoodsResource::getUrl();
    }

    protected function handleRecordCreation(array $data): Model
    {
        /** @var MallGoods $goods */
        $goods = MallGoods::query()->create([
            'goods_sn' => $data['goods_sn'],
            'goods_category_id' => $data['goods_category_id'],
            'goods_name' => $data['goods_name'],
            'subtitle' => $data['subtitle'],
            'main_img' => $data['main_img'],
            'images' => $data['images'],
            'content' => $data['content'],
        ]);

        $attrList = MallAttr::query()
            ->whereIn('id', $data['attr'])
            ->get();

        $mapAttrValues = [];

        /** @var MallGoodsAttr $attr */
        foreach ($attrList as $attr) {
            $attr->load([
                'attrValue' => function (HasMany $query) use ($data, $attr) {
                    $query->whereIn('id', $data['attr_value_'.$attr->id]);
                },
            ]);

            $goodsAttr = $goods->attr()->create([
                'attr_name' => $attr->attr_name,
            ]);

            $attr->attrValue->each(function (MallAttrValue $attrValue) use ($goodsAttr, &$mapAttrValues) {
                $mapAttrValues[$attrValue->id] = $goodsAttr->attrValue()->create([
                    'attr_value_name' => $attrValue->attr_value_name,
                ])->id;
            });
        }

        $skuData = [];

        foreach ($data as $key => $value) {
            if (! str_contains($key, ':')) {
                continue;
            }

            [$fieldName, $spec] = explode(':', $key);

            if (! in_array($fieldName, ['price', 'stock', 'sku_img'], true)) {
                continue;
            }

            $skuData[$spec][$fieldName] = $value;
        }

        foreach ($skuData as $skuKey => $sku) {
            $parts = explode('-', $skuKey);

            $newSkuKey = array_map(function ($part) use ($mapAttrValues) {
                return $mapAttrValues[$part];
            }, $parts);

            $goods->sku()->create([
                'spec' => implode('-', $newSkuKey),
                'price' => $sku['price'],
                'sku_img' => $sku['sku_img'] ?? null,
                'stock' => $sku['stock'],
            ]);
        }

        return $goods;
    }
}
