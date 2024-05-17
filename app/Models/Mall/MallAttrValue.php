<?php

namespace App\Models\Mall;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MallAttrValue extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'mall_attr_value';

    protected $fillable = [
        'attr_id',
        'attr_value_name',
        'is_disabled',
        'sort',
    ];

}
