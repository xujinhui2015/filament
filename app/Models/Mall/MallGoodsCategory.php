<?php

namespace App\Models\Mall;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MallGoodsCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'mall_goods_category';

    protected $fillable = [
        'parent_id',
        'category_name',
        'is_disabled',
        'sort',
    ];

}
