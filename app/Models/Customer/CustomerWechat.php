<?php

namespace App\Models\Customer;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int|null $id
 * @property int|null $customer_id 会员ID
 * @property string|null $mini_openid 小程序openid
 * @property Carbon $deleted_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @method static Builder|CustomerWechat query()
 */
class CustomerWechat extends Model
{
    use SoftDeletes;

    protected $table = 'customer_wechat';

    protected $fillable = [
        'customer_id',
        'mini_openid',
    ];
}
