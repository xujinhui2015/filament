<?php

namespace App\Models\Common;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int $area_id
 * @property int $parent_id
 * @property string $name
 *
 * @method static Builder|Area query()
 */
class Area extends BaseModel
{
    protected $table = 'area';

    public $timestamps = false;

}
