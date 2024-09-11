<?php

namespace App\Models\Mall;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $attr_id
 * @property string $attr_value_name 商品属性名称
 * @property int $is_disabled
 * @property int $sort
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static Builder|MallAttrValue query()
 */
class MallAttrValue extends BaseModel
{
    use SoftDeletes;

    protected $table = 'mall_attr_value';

}
