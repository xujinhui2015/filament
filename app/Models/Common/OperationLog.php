<?php

namespace App\Models\Common;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * @property int|null $id
 * @property string|null $loggable_type 关联类型
 * @property int|null $loggable_id 关联ID
 * @property int $user_id 操作人
 * @property string|null $action 动作
 * @property string|null $operation 操作说明
 * @property Carbon $created_at
 *
 * @method static Builder|OperationLog query()
 */
class OperationLog extends BaseModel
{
    protected $table = 'operation_log';

    const null UPDATED_AT = null;

    /**
     * 关联到一个可记录日志的对象
     */
    public function loggable(): MorphTo
    {
        return $this->morphTo();
    }

}
