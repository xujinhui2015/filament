<?php

namespace App\Models\Mall;

use App\Models\BaseModel;
use App\Support\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * @property int|null $id
 * @property string|null $goods_sn 商品代码
 * @property int|null $goods_category_id 商品分类ID
 * @property string|null $goods_name 商品名称
 * @property string|null $subtitle 商品副标题
 * @property string|null $main_img 商品主图
 * @property string|null $images 商品轮播图
 * @property string|null $content 商品详情
 * @property int $is_sale 是否上架0否1是
 * @property Carbon $deleted_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property MallGoodsCategory $category
 * @property Collection|MallGoodsAttr[] $attr
 * @property Collection|MallGoodsSku[] $sku
 *
 * @method static Builder|MallGoods query()
 */
class MallGoods extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'mall_goods';

    protected $casts = [
        'images' => 'array',
        'sku_min_price' => MoneyCast::class, // withMin('sku', 'price') 的时候调用
    ];

    protected $fillable = [
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

    public function attr(): HasMany
    {
        return $this->hasMany(MallGoodsAttr::class, 'goods_id');
    }

    public function sku(): HasMany
    {
        return $this->hasMany(MallGoodsSku::class, 'goods_id');
    }

}
