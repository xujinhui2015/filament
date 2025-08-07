<?php

namespace App\Models;

use App\Support\Traits\FormatModelDateTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class BaseModel extends Model
{
    use FormatModelDateTrait, LogsActivity;

    /**
     * 默认分页数量
     */
    protected $perPage = 10;

    /**
     * 所有模型允许批量赋值
     */
    protected $guarded = [];

    /**
     * 基础分页方法, 超过100条数据返回100条
     */
    public function getPerPage(): int
    {
        return min(request()->input('per_page', $this->perPage), 100);
    }

    public static function getTableName(): string
    {
        return (new static)->getTable();
    }

    /**
     * 记录操作日志
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }
}
