<?php

namespace App\Models\Mall;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MallExpress extends Model
{
    use SoftDeletes;

    protected $table = 'mall_express';

    protected $fillable = [
        'express_name',
    ];
}
