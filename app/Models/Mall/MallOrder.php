<?php

namespace App\Models\Mall;

use App\Enums\Mall\MallOrderAdjustAdjustTypeEnum;
use App\Models\BaseModel;
use App\Models\Customer\Customer;
use App\Support\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * @property int|null $id
 * @property int|null $customer_id
 * @property string|null $order_no 订单号
 * @property int|null $order_status 订单状态0待付款1待发货2待收货3退款处理4已完成5已关闭6锁单状态
 * @property int|null $order_money 订单金额
 * @property int|null $order_fact_money 订单实付金额
 * @property int|null $order_source 订单来源0直接下单1购物车
 * @property int|null $payment 支付方式0余额支付1微信支付
 * @property string|null $name 收货人姓名
 * @property string|null $phone 收货人电话
 * @property string|null $province 省
 * @property string|null $city 市
 * @property string|null $district 区
 * @property string|null $address 详细地址
 * @property Carbon $last_pay_time 最后付款时间
 * @property Carbon $pay_time 付款时间
 * @property Carbon $delivery_time 发货时间
 * @property Carbon $finish_time 完成时间
 * @property Carbon $cancel_time 取消时间
 * @property Carbon $turnoff_time 关闭时间
 * @property string $logistics_name 物流公司名称
 * @property string $logistics_no 物流单号
 * @property string $buyer_remark 买家留言
 * @property string $seller_message 卖家留言
 * @property string $prepay_id 微信支付ID
 * @property Carbon $deleted_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Collection|MallOrderDetail[] $detail
 * @property Collection|MallOrderAdjust[] $adjust
 * @property Customer $customer
 * @property Collection|MallOrderOperationLog[] $operationLog
 * @property $full_address
 * @property $postage
 *
 * @method static Builder|MallOrder query()
 */
class MallOrder extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'mall_order';

    protected $casts = [
        'last_pay_time' => 'datetime',
        'delivery_time' => 'datetime',
        'finish_time' => 'datetime',
        'cancel_time' => 'datetime',
        'turnoff_time' => 'datetime',
    ];

    protected $fillable = [
        'customer_id',
        'order_no',
        'order_status',
        'order_money',
        'order_fact_money',
        'order_source',
        'payment',
        'name',
        'phone',
        'province',
        'city',
        'district',
        'address',
        'last_pay_time',
        'pay_time',
        'delivery_time',
        'finish_time',
        'cancel_time',
        'turnoff_time',
        'logistics_name',
        'logistics_no',
        'buyer_remark',
        'seller_message',
        'prepay_id',
    ];

    protected function casts(): array
    {
        return [
            'order_money' => MoneyCast::class,
            'order_fact_money' => MoneyCast::class,
        ];
    }

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

    public function operationLog(): HasMany
    {
        return $this->hasMany(MallOrderOperationLog::class, 'order_id');
    }

    /**
     * 获取完整地址
     */
    public function getFullAddressAttribute(): string
    {
        return implode(' ', [
            $this->province,
            $this->city,
            $this->district,
            $this->address
        ]);
    }

    private function getSumAdjustPrice(MallOrderAdjustAdjustTypeEnum $adjustAdjustTypeEnum): float
    {
        return money_cast_get($this->adjust()->where('adjust_type', $adjustAdjustTypeEnum)->sum('adjust_price'));
    }

    /**
     *  获取运费
     */
    public function getPostageAttribute(): float
    {
        return $this->getSumAdjustPrice(MallOrderAdjustAdjustTypeEnum::Postage);
    }

}
