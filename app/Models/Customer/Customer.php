<?php

namespace App\Models\Customer;

use App\Models\BaseModel;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $id
 * @property string|null $nickname 昵称
 * @property string|null $avatar_url 头像
 * @property string|null $phone 手机号
 * @property double $balance 余额
 * @property int $points 积分
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Collection|CustomerBalanceRecord[] $balanceRecords
 * @property Collection|CustomerPointsRecord[] $pointsRecords
 * @property CustomerWechat $wechat
 *
 * @method static Builder|Customer query()
 */
class Customer extends BaseModel implements AuthenticatableContract
{
    use HasApiTokens, HasFactory, SoftDeletes, Authenticatable;

    protected $table = 'customer';

    public function balanceRecords(): HasMany
    {
        return $this->hasMany(CustomerBalanceRecord::class);
    }

    public function pointsRecords(): HasMany
    {
        return $this->hasMany(CustomerPointsRecord::class);
    }

    public function wechat(): HasOne
    {
        return $this->hasOne(CustomerWechat::class);
    }
}
