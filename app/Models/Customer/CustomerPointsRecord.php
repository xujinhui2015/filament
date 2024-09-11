<?php

namespace App\Models\Customer;

use App\Enums\Customer\CustomerPointsSceneTypeEnum;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $customer_id 会员ID
 * @property string $change_explain 说明
 * @property CustomerPointsSceneTypeEnum $scene_type 场景类型
 * @property int $points 变动积分
 * @property int $relation_id 关联ID
 * @property string|null $remark 备注
 * @property Carbon|null $created_at
 *
 * @method static Builder|CustomerPointsRecord query()
 */
class CustomerPointsRecord extends BaseModel
{

    protected $table = 'customer_points_record';

    protected $casts = [
        'scene_type' => CustomerPointsSceneTypeEnum::class,
    ];

    const null UPDATED_AT = null;

}
