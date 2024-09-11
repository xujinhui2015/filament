<?php

namespace App\Models\Customer;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $customer_id 会员ID
 * @property string $name 姓名
 * @property string $phone 电话
 * @property string $province 省
 * @property string $city 市
 * @property string $district 区
 * @property string $address 详细地址
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static Builder|CustomerAddress query()
 */
class CustomerAddress extends BaseModel
{
    use SoftDeletes;

    protected $table = 'customer_address';
}
