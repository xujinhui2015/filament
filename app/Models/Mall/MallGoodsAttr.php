<?php

namespace App\Models\Mall;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MallGoodsAttr extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'mall_goods_attrs';

}
