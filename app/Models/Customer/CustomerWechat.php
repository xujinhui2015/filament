<?php

namespace App\Models\Customer;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int|null $id
 * @property int|null $customer_id 会员ID
 * @property string|null $mini_openid 小程序openid
 * @property Carbon $deleted_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Customer $customer
 *
 * @method static Builder|CustomerWechat query()
 */
class CustomerWechat extends BaseModel
{
    use SoftDeletes;

    protected $table = 'customer_wechat';

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
