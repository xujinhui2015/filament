<?php

namespace App\Models\Customer;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int|null $id
 * @property int|null $customer_id 会员ID
 * @property string|null $name 姓名
 * @property string|null $phone 电话
 * @property string|null $province 省
 * @property string|null $city 市
 * @property string|null $district 区
 * @property string|null $address 详细地址
 * @property Carbon $deleted_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @method static Builder|CustomerAddress query()
 */
class CustomerAddress extends BaseModel
{
    use SoftDeletes;

    protected $table = 'customer_address';
}
