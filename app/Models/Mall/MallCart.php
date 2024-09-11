<?php

namespace App\Models\Mall;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $customer_id 会员ID
 * @property int $goods_id
 * @property int $goods_sku_id
 * @property int $goods_number 购买商品数量
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property MallGoodsSku $sku
 * @property MallGoods $goods
 *
 * @method static Builder|MallCart query()
 */
class MallCart extends BaseModel
{
    use SoftDeletes;

    protected $table = 'mall_cart';

    public function sku(): BelongsTo
    {
        return $this->belongsTo(MallGoodsSku::class, 'goods_sku_id');
    }

    public function goods(): BelongsTo
    {
        return $this->belongsTo(MallGoods::class, 'goods_id');
    }

}
