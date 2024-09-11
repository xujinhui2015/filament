<?php

namespace App\Models\Mall;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property int $goods_id 商品ID
 * @property string $attr_name 商品属性名称
 * @property int $is_disabled
 * @property int $sort
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Collection|MallGoodsAttrValue[] $attrValue
 *
 * @method static Builder|MallGoodsAttr query()
 */
class MallGoodsAttr extends BaseModel
{
    use SoftDeletes;

    protected $table = 'mall_goods_attr';

    public function attrValue(): HasMany
    {
        return $this->hasMany(MallGoodsAttrValue::class, 'goods_attr_id');
    }

}
