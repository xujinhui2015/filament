<?php

namespace App\Support\Traits;

trait EnumTrait
{
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
        return ! $this->isEq($value);
    }

    /**
     * 获取所有字段
     */
    public static function options(): array
    {
        $cases = self::cases();
        $result = [];
        foreach ($cases as $case) {
            $result[$case->value] = $case->text();
        }

        return $result;
    }

    /**
     * 首字母小写
     */
    public function firstLower(): string
    {
        return lcfirst($this->name);
    }
}
