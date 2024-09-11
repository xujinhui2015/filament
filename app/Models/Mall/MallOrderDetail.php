<?php

namespace App\Models\Mall;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $order_id
 * @property int $goods_id
 * @property int $goods_sku_id
 * @property string $goods_name 商品名称
 * @property string $goods_spec 商品规格
 * @property string $goods_image 商品图片
 * @property double $goods_price 商品价格
 * @property int $goods_number 购买商品数量
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
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
