<?php

namespace App\Models\Mall;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MallOrderDetail extends BaseModel
{
    use HasFactory,  SoftDeletes;

    protected $table = 'mall_order_details';

}
