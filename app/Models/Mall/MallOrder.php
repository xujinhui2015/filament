<?php

namespace App\Models\Mall;

use App\Enums\Mall\MallOrderAdjustAdjustTypeEnum;
use App\Enums\Mall\MallOrderOrderSourceEnum;
use App\Enums\Mall\MallOrderOrderStatusEnum;
use App\Enums\Mall\MallOrderPaymentEnum;
use App\Models\BaseModel;
use App\Models\Common\OperationLog;
use App\Models\Customer\Customer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property int $customer_id
 * @property string $order_no 订单号
 * @property MallOrderOrderStatusEnum $order_status 订单状态0待付款1待发货2待收货3退款处理4已完成5已关闭6锁单状态7已取消
 * @property double $order_money 订单金额
 * @property double $order_fact_money 订单实付金额
 * @property MallOrderOrderSourceEnum $order_source 订单来源0直接下单1购物车
 * @property MallOrderPaymentEnum|null $payment 支付方式0余额支付1微信支付
 * @property string $name 收货人姓名
 * @property string $phone 收货人电话
 * @property string $province 省
 * @property string $city 市
 * @property string $district 区
 * @property string $address 详细地址
 * @property Carbon|null $last_pay_time 最后付款时间
 * @property Carbon|null $pay_time 付款时间
 * @property Carbon|null $delivery_time 发货时间
 * @property Carbon|null $finish_time 完成时间
 * @property Carbon|null $cancel_time 取消时间
 * @property Carbon|null $turnoff_time 关闭时间
 * @property string|null $logistics_name 物流公司名称
 * @property string|null $logistics_no 物流单号
 * @property string|null $buyer_remark 买家留言
 * @property string|null $seller_message 卖家留言
 * @property string|null $prepay_id 微信支付ID
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property $full_address attribute:完整地址
 * @property $postage attribute:运费
 * @property Collection|MallOrderDetail[] $detail
 * @property Collection|MallOrderAdjust[] $adjust
 * @property Customer $customer
 * @property Collection|OperationLog[] $operationLog
 *
 * @method static Builder|MallOrder query()
 */
class MallOrder extends BaseModel
{
    use SoftDeletes;

    protected $table = 'mall_order';

    protected $casts = [
        'last_pay_time' => 'datetime',
        'delivery_time' => 'datetime',
        'finish_time' => 'datetime',
        'cancel_time' => 'datetime',
        'turnoff_time' => 'datetime',
        'order_status' => MallOrderOrderStatusEnum::class,
        'order_source' => MallOrderOrderSourceEnum::class,
        'payment' => MallOrderPaymentEnum::class,
    ];

    public function detail(): HasMany
    {
        return $this->hasMany(MallOrderDetail::class, 'order_id');
    }

    public function adjust(): HasMany
    {
        return $this->hasMany(MallOrderAdjust::class, 'order_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function operationLog(): MorphMany
    {
        return $this->morphMany(OperationLog::class, 'loggable');
    }

    /**
     * 完整地址
     */
    public function fullAddress(): Attribute
    {
        return Attribute::make(
            get: fn() => implode(' ', [
                $this->province,
                $this->city,
                $this->district,
                $this->address
            ]),
        );
    }

    private function getSumAdjustPrice(MallOrderAdjustAdjustTypeEnum $adjustAdjustTypeEnum): float
    {
        return $this->adjust()->where('adjust_type', $adjustAdjustTypeEnum)->sum('adjust_price');
    }

    /**
     *  运费
     */
    public function postage(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->getSumAdjustPrice(MallOrderAdjustAdjustTypeEnum::Postage),
        );
    }

}
