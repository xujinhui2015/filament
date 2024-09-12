<?php

namespace App\Models\Mall;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name 退货人名称
 * @property string $phone 退货人电话
 * @property string $province 省
 * @property string $city 市
 * @property string $district 区
 * @property string $address 详细地址
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property $contact_info attribute:联系方式
 * @property $full_address attribute
 *
 * @method static Builder|MallRefundAddress query()
 */
class MallRefundAddress extends BaseModel
{
    use SoftDeletes;

    protected $table = 'mall_refund_address';

    /**
     *  联系方式
     */
    public function contactInfo(): Attribute
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

}
