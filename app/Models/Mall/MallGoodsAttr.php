<?php

namespace App\Models\Mall;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * @property int|null $id
 * @property int|null $goods_id 商品ID
 * @property string|null $attr_name 商品属性名称
 * @property int|null $is_disabled
 * @property int|null $sort
 * @property Carbon $deleted_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Collection|MallGoodsAttrValue[] $value
 *
 * @method static Builder|MallGoodsAttr query()
 */
class MallGoodsAttr extends BaseModel
{
    use SoftDeletes;

    protected $table = 'mall_goods_attr';

    protected $fillable = [
        'goods_id',
        'attr_name',
        'is_disabled',
        'sort',
    ];

    public function value(): HasMany
    {
        return $this->hasMany(MallGoodsAttrValue::class, 'goods_attr_id');
    }

}
