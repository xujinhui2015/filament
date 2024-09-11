<?php

namespace App\Models\Mall;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $order_id
 * @property int $order_detail_id
 * @property string $adjust_type 调整类型
 * @property double $adjust_price 调整价格
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static Builder|MallOrderAdjust query()
 */
class MallOrderAdjust extends BaseModel
{
    use SoftDeletes;

    protected $table = 'mall_order_adjust';

}
