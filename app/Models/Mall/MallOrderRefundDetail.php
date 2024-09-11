<?php

namespace App\Models\Mall;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $order_refund_id
 * @property int $order_detail_id
 * @property int $goods_id
 * @property int $goods_sku_id
 * @property int $refund_number 退货数量
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property MallOrderDetail $orderDetail
 *
 * @method static Builder|MallOrderRefundDetail query()
 */
class MallOrderRefundDetail extends BaseModel
{
    use SoftDeletes;

    protected $table = 'mall_order_refund_detail';

    public function orderDetail(): BelongsTo
    {
        return $this->belongsTo(MallOrderDetail::class, 'order_detail_id');
    }
}
