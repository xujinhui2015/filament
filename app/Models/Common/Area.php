<?php

namespace App\Models\Common;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int|null $area_id
 * @property int|null $parent_id
 * @property string|null $name
 *
 * @method static Builder|Area query()
 */
class Area extends BaseModel
{
    protected $table = 'area';

    public $timestamps = false;

}
