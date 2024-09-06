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
 * @property string|null $logistics_company_name 物流公司名称
 * @property string|null $logistics_no 快递单号
 * @property string|null $name 退货人姓名
 * @property string|null $phone 退货人电话
 * @property string|null $province 省
 * @property string|null $city 市
 * @property string|null $district 区
 * @property string|null $address 详细地址
 * @property Carbon $deleted_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @method static Builder|MallOrderRefundLogistics query()
 */
class MallOrderRefundLogistics extends BaseModel
{
    use SoftDeletes;

    protected $table = 'mall_order_refund_logistics';
}
