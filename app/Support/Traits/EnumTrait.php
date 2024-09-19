<?php

namespace App\Support\Traits;

use UnitEnum;

trait EnumTrait
{

    /**
     * 获取值的枚举，会对值进行判断
     */
    public static function fromEnum(mixed $value): self
    {
        return is_subclass_of($value, UnitEnum::class) ? $value : self::tryFrom($value);
    }

    /**
     * 判断枚举是否等于值
     */
    public function isEq($value): bool
    {
        if (is_subclass_of($value, UnitEnum::class)) {
            return $this == $value;
        } else {
            return $this->value == $value;
        }
    }

    /**
     * 判断枚举是否不等于值
     */
    public function isNeq($value): bool
    {
        return !$this->isEq($value);
    }

    /**
     * 判断是否包含当前值
     */
    public function in(array $enums): bool
    {
        return in_array($this, $enums);
    }

    /**
     * 获取所有字段
     */
    public static function options(): array
    {
        $cases = self::cases();
        $result = [];
        foreach ($cases as $case) {
            $result[$case->value] = $case->getLabel();
        }

        return $result;
    }

    public static function values(): array
    {
        return array_map(function ($value) {
            return $value->value;
        }, self::cases());
    }

}
