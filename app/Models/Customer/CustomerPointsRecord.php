<?php

namespace App\Models\Customer;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * @property int|null $id
 * @property int|null $customer_id 会员ID
 * @property string|null $change_explain 说明
 * @property string|null $scene_type 场景类型
 * @property int|null $points 变动积分
 * @property int|null $relation_id 关联ID
 * @property string $remark 备注
 * @property Carbon $created_at
 *
 * @method static Builder|CustomerPointsRecord query()
 */
class CustomerPointsRecord extends BaseModel
{

    protected $table = 'customer_points_records';

    const UPDATED_AT = null;

    protected $fillable = [
        'customer_id',
        'change_explain',
        'scene_type',
        'points',
        'relation_id',
        'remark',
    ];
}