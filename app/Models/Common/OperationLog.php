<?php

namespace App\Models\Common;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\MorphTo;

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
