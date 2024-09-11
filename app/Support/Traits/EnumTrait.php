<?php

namespace App\Support\Traits;

trait EnumTrait
{

    /**
     * 获取值的枚举，会对值进行判断
     */
    public static function fromEnum(mixed $value): self
    {
        return $value instanceof self ? $value : self::tryFrom($value);
    }

    /**
     * 判断枚举是否等于值
     */
    public function isEq($value): bool
    {
        return $this->value == $value;
    }

    /**
     * 判断枚举是否不等于值
     */
    public function isNeq($value): bool
    {
        return !$this->isEq($value);
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

}
