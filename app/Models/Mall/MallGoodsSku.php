<?php

namespace App\Models\Mall;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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

    protected $appends = [
        'spec_text'
    ];

    public function getSpecTextAttribute(): string
    {
        return MallAttrValue::query()
            ->whereIn('id', explode('-', $this->spec))
            ->pluck('attr_value_name')
            ->implode('-');
    }

}
