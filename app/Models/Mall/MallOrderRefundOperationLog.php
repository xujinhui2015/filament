<?php

namespace App\Models\Mall;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 *
 * @method static Builder|MallOrderRefundOperationLog query()
 */
class MallOrderRefundOperationLog extends BaseModel
{
    use SoftDeletes;

    const null UPDATED_AT = null;

    protected $table = 'mall_order_refund_operation_log';

}
