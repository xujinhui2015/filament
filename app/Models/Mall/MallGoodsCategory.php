<?php

namespace App\Models\Mall;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SolutionForest\FilamentTree\Concern\ModelTree;

class MallGoodsCategory extends Model
{
    use HasFactory, SoftDeletes, ModelTree;

    protected $table = 'mall_goods_category';

    protected $fillable = [
        'parent_id',
        'title',
        'sort',
        'is_disabled',
    ];

}
