<?php

namespace App\Models\Mall;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $order_refund_id
 * @property string|null $logistics_company_name 物流公司名称
 * @property string|null $logistics_no 快递单号
 * @property string $name 退货人名称
 * @property string $phone 退货人电话
 * @property string $province 省
 * @property string $city 市
 * @property string $district 区
 * @property string $address 详细地址
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property $contact_info attribute:退货联系信息
 *
 * @method static Builder|MallOrderRefundLogistics query()
 */
class MallOrderRefundLogistics extends BaseModel
{
    use SoftDeletes;

    protected $table = 'mall_order_refund_logistics';

    /**
     * 退货联系信息
     */
    protected function contactInfo(): Attribute
    {
        return Attribute::make(
            get: fn() => implode(' ', [
                $this->name,
                $this->phone,
                $this->province,
                $this->city,
                $this->district,
                $this->address
            ]),
        );
    }
}
