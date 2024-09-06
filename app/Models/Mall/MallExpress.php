<?php

namespace App\Models\Mall;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * @property int|null $id
 * @property string|null $express_name 快递公司名称
 * @property Carbon $deleted_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @method static Builder|MallExpress query()
 */
class MallExpress extends Model
{
    use SoftDeletes;

    protected $table = 'mall_express';
}
