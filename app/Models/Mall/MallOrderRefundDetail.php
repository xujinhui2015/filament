<?php

namespace App\Models\Mall;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * @property int|null $id
 * @property int|null $order_refund_id
 * @property int|null $goods_id
 * @property int|null $goods_sku_id
 * @property int|null $refund_number 退货数量
 * @property Carbon $deleted_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @method static Builder|MallOrderRefundDetail query()
 */
class MallOrderRefundDetail extends BaseModel
{
    use SoftDeletes;

    protected $table = 'mall_order_refund_detail';

    protected $fillable = [
        'order_refund_id',
        'goods_id',
        'goods_sku_id',
        'refund_number',
    ];
}
