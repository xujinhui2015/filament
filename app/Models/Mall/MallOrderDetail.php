<?php

namespace App\Models\Mall;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int|null $id
 * @property int|null $order_id
 * @property int|null $goods_id
 * @property int|null $goods_sku_id
 * @property string|null $goods_name 商品名称
 * @property string|null $goods_spec 商品规格
 * @property string|null $goods_image 商品图片
 * @property double|null $goods_price 商品价格
 * @property int|null $goods_number 购买商品数量
 * @property Carbon $deleted_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property MallOrder $order
 * @property MallGoods $goods
 * @property MallGoodsSku $goodsSku
 *
 * @method static Builder|MallOrderDetail query()
 */
class MallOrderDetail extends BaseModel
{
    use SoftDeletes;

    protected $table = 'mall_order_detail';

    protected $fillable = [
        'order_id',
        'goods_id',
        'goods_sku_id',
        'goods_name',
        'goods_spec',
        'goods_image',
        'goods_price',
        'goods_number',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(MallOrder::class, 'order_id');
    }

    public function goods(): BelongsTo
    {
        return $this->belongsTo(MallGoods::class, 'goods_id');
    }

    public function goodsSku(): BelongsTo
    {
        return $this->belongsTo(MallGoodsSku::class, 'goods_sku_id');
    }

}
