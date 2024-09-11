<?php

namespace App\Models\Customer;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $customer_id 会员ID
 * @property string $mini_openid 小程序openid
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
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
