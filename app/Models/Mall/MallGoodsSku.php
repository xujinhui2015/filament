<?php

namespace App\Models\Mall;

use App\Enums\Cache\MallCacheKeyEnum;
use App\Models\BaseModel;
use App\Support\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * @property int|null $id
 * @property int|null $goods_id
 * @property string|null $spec 规格明细
 * @property int|null $price 单价
 * @property string $sku_img 规格图片
 * @property int|null $stock 库存
 * @property Carbon $deleted_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property $spec_text
 * @property MallGoods $goods
 *
 * @method static Builder|MallGoodsSku query()
 */
class MallGoodsSku extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'mall_goods_sku';

    protected $fillable = [
        'goods_id',
        'spec',
        'price',
        'sku_img',
        'stock',
    ];

    protected function casts(): array
    {
        return [
            'price' => MoneyCast::class,
        ];
    }

    protected $appends = [
        'spec_text'
    ];

    public function getSpecTextAttribute(): string
    {
        return MallCacheKeyEnum::SkuSpec->cacheData([$this->spec], function () {
            return MallAttrValue::query()
                ->whereIn('id', explode('-', $this->spec))
                ->pluck('attr_value_name')
                ->implode('-');
        });
    }

    public function goods(): BelongsTo
    {
        return $this->belongsTo(MallGoods::class, 'goods_id');
    }
}
