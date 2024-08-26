<?php

namespace App\Models\Mall;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * @property int|null $id
 * @property int|null $order_id
 * @property string|null $refund_order_no 退款订单号
 * @property int|null $refund_type 退款类型0仅退款1退货退款
 * @property int|null $refund_status 退款类型0申请退款1同意退款2买家退货3卖家确认收货4确认退款5退款成功6退款失败7退款关闭(仅退款只有014567)
 * @property double|null $refund_money 退款金额
 * @property double|null $refund_bonus 退款积分
 * @property string|null $phone 退货人联系电话
 * @property string $refund_reason 退款原因
 * @property string $buyer_message 买家留言
 * @property string $buyer_images 买家图片凭证
 * @property string $seller_message 卖家留言
 * @property Carbon $deleted_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @method static Builder|MallOrderRefund query()
 */
class MallOrderRefund extends BaseModel
{
    use SoftDeletes;

    protected $table = 'mall_order_refund';

    protected $casts = [
        'attachments' => 'array',
    ];

    protected $fillable = [
        'order_id',
        'refund_order_no',
        'refund_type',
        'refund_status',
        'refund_money',
        'refund_bonus',
        'phone',
        'refund_reason',
        'buyer_message',
        'buyer_images',
        'seller_message',
    ];
}
