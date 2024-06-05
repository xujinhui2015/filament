<?php

namespace App\Models\Mall;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * @property int|null $id
 * @property int|null $customer_id 会员ID
 * @property int|null $goods_id
 * @property int|null $goods_sku_id
 * @property int|null $goods_number 购买商品数量
 * @property Carbon $deleted_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @method static Builder|MallCart query()
 */
class MallCart extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'mall_cart';

    protected $fillable = [
        'customer_id',
        'goods_id',
        'goods_sku_id',
        'goods_number',
    ];

    public function sku(): BelongsTo
    {
        return $this->belongsTo(MallGoodsSku::class, 'goods_sku_id');
    }

    public function goods(): BelongsTo
    {
        return $this->belongsTo(MallGoods::class, 'goods_id');
    }

}
