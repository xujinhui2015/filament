<?php

namespace App\Models\Customer;

use App\Models\BaseModel;
use App\Support\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int|null $id
 * @property string $nickname 昵称
 * @property string $avatar_url 头像
 * @property string $phone 手机号
 * @property int|null $balance 余额
 * @property int|null $points 积分
 * @property Carbon $deleted_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Collection|CustomerBalanceRecord[] $balanceRecords
 * @property Collection|CustomerPointsRecord[] $pointsRecords
 * @property CustomerWechat $wechat
 *
 * @method static Builder|Customer query()
 */
class Customer extends BaseModel
{
    use HasApiTokens,HasFactory,SoftDeletes;

    protected $table = 'customer';

    protected $casts = [
        'balance' => MoneyCast::class,
    ];

    protected $fillable = [
        'nickname',
        'avatar_url',
        'phone',
        'balance',
        'points',
    ];

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
