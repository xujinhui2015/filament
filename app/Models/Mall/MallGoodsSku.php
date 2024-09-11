<?php

namespace App\Models\Mall;

use App\Enums\Cache\MallCacheKeyEnum;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * @property int|null $id
 * @property int|null $goods_id
 * @property string|null $spec 规格明细
 * @property double|null $price 单价
 * @property string $sku_img 规格图片
 * @property int|null $stock 库存
 * @property Carbon $deleted_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property $spec_textattribute
 * @property MallGoods $goods
 *
 * @method static Builder|MallGoodsSku query()
 */
class MallGoodsSku extends BaseModel
{
    use SoftDeletes;

    protected $table = 'mall_goods_sku';

    protected $appends = [
        'spec_text'
    ];

    public function specText(): Attribute
    {
        return Attribute::make(
            get: fn() => MallCacheKeyEnum::SkuSpec->cacheData([$this->spec], function () {
                return MallAttrValue::query()
                    ->whereIn('id', explode('-', $this->spec))
                    ->pluck('attr_value_name')
                    ->implode('-');
            }),
        );
    }

    public function goods(): BelongsTo
    {
        return $this->belongsTo(MallGoods::class, 'goods_id');
    }
}
