<?php

namespace App\Support\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class MoneyCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): float
    {
        return money_cast_get($value);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): float
    {
        return money_cast_set($value);
    }
}
