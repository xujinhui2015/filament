<?php

namespace App\Models\Mall;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * @property int|null $id
 * @property string|null $name 退货人姓名
 * @property string|null $phone 退货人电话
 * @property string|null $province 省
 * @property string|null $city 市
 * @property string|null $district 区
 * @property string|null $address 详细地址
 * @property Carbon $deleted_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property $contact_info
 *
 * @method static Builder|MallRefundAddress query()
 */
class MallRefundAddress extends BaseModel
{
    use SoftDeletes;

    protected $table = 'mall_refund_address';

    /**
     * 获取联系方式
     */
    public function getContactInfoAttribute(): string
    {
        return implode(' ', [
            $this->name,
            $this->phone,
            $this->province,
            $this->city,
            $this->district,
            $this->address
        ]);
    }

}
