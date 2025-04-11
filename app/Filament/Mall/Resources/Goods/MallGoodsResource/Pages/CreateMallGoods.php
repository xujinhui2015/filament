<?php

namespace App\Filament\Mall\Resources\Goods\MallGoodsResource\Pages;

use App\Filament\Mall\Resources\Goods\MallGoodsResource;
use App\Models\Mall\MallAttr;
use App\Models\Mall\MallAttrValue;
use App\Models\Mall\MallGoods;
use App\Models\Mall\MallGoodsAttr;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Exceptions\Halt;
use Filament\Support\Facades\FilamentView;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use function Filament\Support\is_app_url;

class CreateMallGoods extends CreateRecord
{
    protected static string $resource = MallGoodsResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? MallGoodsResource::getUrl();
    }

    /**
     * @throws Halt
     */
    protected function beforeCreate(): void
    {
        $data = $this->form->getState();

        DB::transaction(function () use ($data) {
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

            // 创建商品规格
            $mapAttrValues = [];
            /** @var MallGoodsAttr $attr */
            foreach ($attrList as $attr) {
                $attr->load([
                    'attrValue' => function (HasMany $query) use ($data, $attr) {
                        $query->whereIn('id', $data['attr_value_' . $attr->id]);
                    }
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

            // 创建SKU数据
            $skuData = [];
            foreach ($data as $key => $value) {
                if (!str_contains($key, ':')) {
                    continue;
                }
                list($fieldName, $spec) = explode(":", $key);
                // 排除掉忽略的属性
                if (!in_array($fieldName, ['price', 'stock', 'sku_img'])) {
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
        });

        $this->callHook('afterCreate');

        $this->commitDatabaseTransaction();

        $this->rememberData();

        $this->getCreatedNotification()?->send();

        $redirectUrl = $this->getRedirectUrl();

        $this->redirect($redirectUrl, navigate: FilamentView::hasSpaMode() && is_app_url($redirectUrl));

        // 停止接下来的操作
        $this->halt();
    }

}
