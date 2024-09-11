<?php

namespace App\Models\Mall;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use SolutionForest\FilamentTree\Concern\ModelTree;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $parent_id
 * @property string $title 分类名称
 * @property int $is_disabled
 * @property int $sort
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static Builder|MallGoodsCategory query()
 */
class MallGoodsCategory extends BaseModel
{
    use SoftDeletes, ModelTree;

    protected $table = 'mall_goods_category';

}
