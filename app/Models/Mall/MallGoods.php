<?php

namespace App\Models\Mall;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MallGoods extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'mall_goods';

    protected $fillable = [
      'id',
      'goods_sn',
      'goods_category_id',
      'goods_name',
      'subtitle',
      'main_img',
      'images',
      'content',
      'is_sale',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(MallGoodsCategory::class, 'goods_category_id');
    }

}
