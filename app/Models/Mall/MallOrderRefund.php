<?php

namespace App\Models\Mall;

use App\Enums\Mall\MallOrderRefundRefundStatusEnum;
use App\Enums\Mall\MallOrderRefundRefundTypeEnum;
use App\Models\BaseModel;
use App\Models\Common\OperationLog;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property int $order_id
 * @property string $refund_order_no 退款订单号
 * @property MallOrderRefundRefundTypeEnum $refund_type 退款类型0仅退款1退货退款
 * @property MallOrderRefundRefundStatusEnum $refund_status 退款状态0申请退款1同意退款2买家退货3卖家确认收货4确认退款5退款成功6退款失败7退款关闭(仅退款只有014567)
 * @property double $refund_money 退款金额
 * @property string $phone 退货人联系电话
 * @property string|null $refund_reason 退款原因
 * @property string|null $buyer_message 买家留言
 * @property string|null $buyer_images 买家图片凭证
 * @property string|null $seller_message 卖家留言
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property MallOrder $order
 * @property Collection|MallOrderRefundDetail[] $detail
 * @property Collection|OperationLog[] $operationLog
 * @property MallOrderRefundLogistics $logistics
 *
 * @method static Builder|MallOrderRefund query()
 */
class MallOrderRefund extends BaseModel
{
    use SoftDeletes;

    protected $table = 'mall_order_refund';

    protected $casts = [
        'buyer_images' => 'array',
        'refund_type' => MallOrderRefundRefundTypeEnum::class,
        'refund_status' => MallOrderRefundRefundStatusEnum::class,
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(MallOrder::class, 'order_id');
    }

    public function detail(): HasMany
    {
        return $this->hasMany(MallOrderRefundDetail::class, 'order_refund_id');
    }

    public function operationLog(): MorphMany
    {
        return $this->morphMany(OperationLog::class, 'loggable');
    }

    public function logistics(): HasOne
    {
        return $this->hasOne(MallOrderRefundLogistics::class, 'order_refund_id');
    }
}
