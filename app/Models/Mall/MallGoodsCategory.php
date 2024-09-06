<?php

namespace App\Models\Mall;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use SolutionForest\FilamentTree\Concern\ModelTree;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * @property int|null $id
 * @property int|null $parent_id
 * @property string|null $title 分类名称
 * @property int|null $is_disabled
 * @property int|null $sort
 * @property Carbon $deleted_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @method static Builder|MallGoodsCategory query()
 */
class MallGoodsCategory extends BaseModel
{
    use SoftDeletes, ModelTree;

    protected $table = 'mall_goods_category';

}
