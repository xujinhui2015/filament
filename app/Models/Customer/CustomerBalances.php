<?php

namespace App\Models\Customer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
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
 * @method static Builder|CustomerBalances query()
 */
class CustomerBalances extends Model
{
    use HasFactory;

    protected $table = 'customer_balances';

    protected $fillable = [
        'customer_id',
        'change_explain',
        'scene_type',
        'balance',
        'relation_id',
        'remark',
    ];
}
