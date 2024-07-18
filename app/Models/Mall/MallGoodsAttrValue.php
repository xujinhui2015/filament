<?php

namespace App\Models\Mall;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * @property int|null $id
 * @property int|null $goods_attr_id 商城商品规格属性表ID
 * @property string|null $attr_value_name 商品属性值名称
 * @property int|null $is_disabled
 * @property int|null $sort
 * @property Carbon $deleted_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @method static Builder|MallGoodsAttrValue query()
 */
class MallGoodsAttrValue extends BaseModel
{
    use SoftDeletes;

    protected $table = 'mall_goods_attr_value';

    protected $fillable = [
        'goods_attr_id',
        'attr_value_name',
        'is_disabled',
        'sort',
    ];

}
