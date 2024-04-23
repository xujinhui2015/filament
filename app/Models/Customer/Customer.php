<?php

namespace App\Models\Customer;

use App\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * @property int|null $id
 * @property string $nickname 昵称
 * @property string $avatar 头像
 * @property string $phone 手机号
 * @property int|null $balance 余额
 * @property int|null $points 积分
 * @property Carbon $deleted_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @method static Builder|Customer query()
 */
class Customer extends Model
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

    public function balance(): HasMany
    {
        return $this->hasMany(CustomerBalances::class);
    }

    public function points(): HasMany
    {
        return $this->hasMany(CustomerPoints::class);
    }
}
