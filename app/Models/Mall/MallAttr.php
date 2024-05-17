<?php

namespace App\Models\Mall;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MallAttr extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'mall_attr';

    protected $fillable = [
        'attr_name',
        'is_disabled',
        'sort',
    ];

    public function value(): HasMany
    {
        return $this->hasMany(MallAttrValue::class, 'attr_id')->orderBy('sort');
    }

}
