<?php

namespace App\Models\Mall;

use App\Casts\MoneyCast;
use App\Models\BaseModel;
use App\Models\Customer\Customer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
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
 * @property int $user_coupon_id 用户优惠券ID
 * @property string|null $name 收货人姓名
 * @property string|null $phone 收货人电话
 * @property string|null $province 省
 * @property string|null $city 市
 * @property string|null $district 区
 * @property string|null $address 详细地址
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
 * @property Carbon $deleted_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Collection|MallOrderDetail[] $detail
 *
 * @method static Builder|MallOrder query()
 */
class MallOrder extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'mall_order';

    protected $fillable = [
        'customer_id',
        'order_no',
        'order_status',
        'order_money',
        'order_fact_money',
        'order_source',
        'customer_coupon_id',
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

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }


}
