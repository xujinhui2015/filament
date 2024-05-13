<?php

namespace App\Models\Customer;

use App\Casts\MoneyCast;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

/**
 * @property int|null $id
 * @property int|null $customer_id 会员ID
 * @property string|null $change_explain 说明
 * @property string|null $scene_type 场景类型
 * @property int|null $balance 变动金额
 * @property int|null $relation_id 关联ID
 * @property string $remark 备注
 * @property Carbon $created_at
 *
 * @method static Builder|CustomerBalanceRecord query()
 */
class CustomerBalanceRecord extends BaseModel
{
    use HasFactory;

    protected $table = 'customer_balance_record';

    const UPDATED_AT = null;

    protected $casts = [
        'balance' => MoneyCast::class,
    ];

    protected $fillable = [
        'customer_id',
        'change_explain',
        'scene_type',
        'balance',
        'relation_id',
        'remark',
    ];
}
