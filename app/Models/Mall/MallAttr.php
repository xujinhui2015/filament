<?php

namespace App\Models\Mall;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * @property int|null $id
 * @property string|null $attr_name 商品属性名称
 * @property int|null $is_disabled
 * @property int|null $sort
 * @property Carbon $deleted_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Collection|MallAttrValue[] $attrValue
 *
 * @method static Builder|MallAttr query()
 */
class MallAttr extends BaseModel
{
    use SoftDeletes;

    protected $table = 'mall_attr';

    protected $fillable = [
        'attr_name',
        'is_disabled',
        'sort',
    ];

    public function attrValue(): HasMany
    {
        return $this->hasMany(MallAttrValue::class, 'attr_id')->orderBy('sort');
    }

    public static function options(): Collection
    {
        return self::query()
            ->orderByDesc('id')
            ->where('is_disabled', false)
            ->pluck('attr_name', 'id');
    }

}
