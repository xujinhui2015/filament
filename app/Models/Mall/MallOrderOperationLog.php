<?php

namespace App\Models\Mall;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;

/**
 *
 * @method static Builder|MallOrderOperationLog query()
 */
class MallOrderOperationLog extends BaseModel
{
    protected $table = 'mall_order_operation_log';

    const null UPDATED_AT = null;

}
