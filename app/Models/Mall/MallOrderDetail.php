<?php

namespace App\Models\Mall;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * @property int|null $id
 * @property int|null $order_id
 * @property int|null $goods_id
 * @property int|null $goods_sku_id
 * @property string|null $goods_name 商品名称
 * @property string|null $goods_spec 商品规格
 * @property string|null $goods_image 商品图片
 * @property int|null $goods_price 商品价格
 * @property int|null $goods_number 购买商品数量
 * @property Carbon $deleted_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @method static Builder|MallOrderDetail query()
 */
class MallOrderDetail extends BaseModel
{
    use HasFactory,  SoftDeletes;

    protected $table = 'mall_order_detail';

}
