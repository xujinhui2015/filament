<?php

namespace App\Models\Mall;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int|null $id
 * @property int|null $order_id
 * @property int|null $order_detail_id
 * @property string|null $adjust_type 调整类型
 * @property double|null $adjust_price 调整价格
 * @property Carbon $deleted_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @method static Builder|MallOrderAdjust query()
 */
class MallOrderAdjust extends BaseModel
{
    use SoftDeletes;

    protected $table = 'mall_order_adjust';

    protected $fillable = [
        'order_id',
        'adjust_type',
        'adjust_price',
    ];

}
