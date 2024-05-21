<?php

namespace App\Models\Mall;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * @property int|null $id
 * @property string|null $attr_id
 * @property string|null $attr_value_name 商品属性名称
 * @property int|null $is_disabled
 * @property int|null $sort
 * @property Carbon $deleted_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @method static Builder|MallAttrValue query()
 */
class MallAttrValue extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'mall_attr_value';

    protected $fillable = [
        'attr_id',
        'attr_value_name',
        'is_disabled',
        'sort',
    ];

}
