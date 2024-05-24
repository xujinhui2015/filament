<?php

namespace App\Models\Mall;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * @property int|null $id
 * @property int|null $order_id
 * @property int $user_id 操作人
 * @property string|null $action 动作
 * @property string|null $operation 操作说明
 * @property Carbon $created_at
 *
 * @method static Builder|MallOrderOperationLog query()
 */
class MallOrderOperationLog extends BaseModel
{
    protected $table = 'mall_order_operation_log';

    const UPDATED_AT = null;


    protected $fillable = [
        'order_id',
        'user_id',
        'action',
        'operation',
    ];

}
