<?php

namespace App\Models\Mall;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property string $goods_sn 商品代码
 * @property int $goods_category_id 商品分类ID
 * @property string $goods_name 商品名称
 * @property string $subtitle 商品副标题
 * @property string $main_img 商品主图
 * @property string $images 商品轮播图
 * @property string $content 商品详情
 * @property int|null $is_sale 是否上架0否1是
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property MallGoodsCategory $category
 * @property Collection|MallGoodsAttr[] $attr
 * @property Collection|MallGoodsSku[] $sku
 *
 * @method static Builder|MallGoods query()
 */
class MallGoods extends BaseModel
{
    use SoftDeletes;

    protected $table = 'mall_goods';

    protected $casts = [
        'images' => 'array',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(MallGoodsCategory::class, 'goods_category_id');
    }

    public function attr(): HasMany
    {
        return $this->hasMany(MallGoodsAttr::class, 'goods_id');
    }

    public function sku(): HasMany
    {
        return $this->hasMany(MallGoodsSku::class, 'goods_id');
    }

}
