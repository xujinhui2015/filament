<?php

namespace App\Models\Mall;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MallOrderDetail extends Model
{
    use HasFactory,  SoftDeletes;

    protected $table = 'mall_order_details';

}
