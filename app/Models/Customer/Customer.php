<?php

namespace App\Models\Customer;

use App\Casts\MoneyCast;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

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
 * @property CustomerBalanceRecord[] $customerBalances
 * @property CustomerPointsRecord[] $customerPoints
 *
 * @method static Builder|Customer query()
 */
class Customer extends BaseModel
{
    use HasFactory,SoftDeletes;

    protected $table = 'customers';

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

    public function customerBalanceRecords(): HasMany
    {
        return $this->hasMany(CustomerBalanceRecord::class);
    }

    public function customerPointsRecords(): HasMany
    {
        return $this->hasMany(CustomerPointsRecord::class);
    }
}
