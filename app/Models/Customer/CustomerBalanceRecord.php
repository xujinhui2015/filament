<?php

namespace App\Models\Customer;

use App\Enums\Customer\CustomerBalanceSceneTypeEnum;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $customer_id 会员ID
 * @property string $change_explain 说明
 * @property CustomerBalanceSceneTypeEnum $scene_type 场景类型
 * @property double $balance 变动金额
 * @property int $relation_id 关联ID
 * @property string|null $remark 备注
 * @property Carbon|null $created_at
 *
 * @method static Builder|CustomerBalanceRecord query()
 */
class CustomerBalanceRecord extends BaseModel
{

    protected $table = 'customer_balance_record';

    protected $casts = [
        'scene_type' => CustomerBalanceSceneTypeEnum::class,
    ];

    const null UPDATED_AT = null;

}
