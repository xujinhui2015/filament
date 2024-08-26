<?php

namespace App\Models\Mall;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * @property int|null $id
 * @property int|null $order_refund_id
 * @property int $user_id 操作人
 * @property string|null $action 动作
 * @property string|null $operation 操作说明
 * @property Carbon $created_at
 *
 * @method static Builder|MallOrderRefundOperationLog query()
 */
class MallOrderRefundOperationLog extends BaseModel
{
    use SoftDeletes;

    const UPDATED_AT = null;

    protected $table = 'mall_order_refund_operation_log';

    protected $fillable = [
        'order_refund_id',
        'user_id',
        'action',
        'operation',
    ];
}
