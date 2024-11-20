<?php
declare (strict_types=1);

namespace App\Support\Helpers;

/**
 * 精确计算类，支持链式调用
 * 调用方法
 * 保留两位小数的方式精确计算
 * $accuracyCal = new AccuracyCal(1);
 * 以下代表表达式：1 + 2 - 1.1
 * $result = $accuracyCal->add(2)->sub(1.1)->result();
 */
class AccuracyCalcHelper
{
    /**
     * @param string|int|float|null $result 第一个参与计算的值
     * @param int $scale 保留的小数点
     */
    private function __construct(
        private string|int|float|null $result = '0',
        private readonly int          $scale = 2
    )
    {
    }

    /**
     * 通过静态调用方法
     */
    public static function begin(string|int|float|null $result, int $scale = 2): AccuracyCalcHelper
    {
        return new self((string)$result ?? '0', $scale);
    }

    public function setResult(string|int|float|null $number): static
    {
        $this->result = (string)$number ?? '0';
        return $this;
    }

    /**
     * 精确计算：加
     */
    public function add(string|int|float|null $number): static
    {
        $this->result = bcadd($this->result, (string)$number ?? '0', $this->scale);
        return $this;
    }

    /**
     * 精确计算：减
     */
    public function sub(string|int|float|null $number): static
    {
        $this->result = bcsub($this->result, (string)($number) ?? '0', $this->scale);
        return $this;
    }

    /**
     * 精确计算：乘
     */
    public function mul(string|int|float|null $number): static
    {
        $this->result = bcmul($this->result, (string)($number) ?? '0', $this->scale);
        return $this;
    }

    /**
     * 精确计算：除
     */
    public function div(string|int|float|null $number): static
    {
        $this->result = bcdiv($this->result, (string)($number) ?? '0', $this->scale);
        return $this;
    }

    /**
     * 精确计算：百分比
     */
    public function proportion(string|int|float|null $proportion): static
    {
        $this->result = bcmul($this->result, bcdiv((string)$proportion ?? '0', '100', $this->scale + 2), $this->scale);
        return $this;
    }

    /**
     * 对结果取整
     */
    public function toInteger(): static
    {
        $this->result = (string)intval($this->result ?? '0');
        return $this;
    }

    /**
     * 获取计算结果
     */
    public function result(): string
    {
        return (string)$this->result ?? '0';
    }

    /**
     * 是否大于等于某个数
     */
    public function isEgt(string|int|float|null $number = '0'): bool
    {
        return bccomp($this->result ?? '0', (string)$number, $this->scale) != -1;
    }

    /**
     * 是否大于某个数
     */
    public function isGt(string|int|float|null $number = '0'): bool
    {
        return bccomp($this->result ?? '0', (string)$number, $this->scale) == 1;
    }

    /**
     * 是否不等于
     */
    public function isNeq(string|int|float|null $number = '0'): bool
    {
        return bccomp($this->result ?? '0', (string)$number, $this->scale) != 0;
    }

    /**
     * 是否等于
     */
    public function isEq(string|int|float|null $number = '0'): bool
    {
        return bccomp($this->result ?? '0', (string)$number, $this->scale) == 0;
    }

    /**
     * 是否小于等于某个数
     */
    public function isElt(string|int|float|null $number = '0'): bool
    {
        return bccomp($this->result ?? '0', (string)$number, $this->scale) != 1;
    }

    /**
     * 是否小于某个数
     */
    public function isLt(string|int|float|null $number = '0'): bool
    {
        return bccomp($this->result ?? '0', (string)$number, $this->scale) == -1;
    }

    /**
     * 取绝对值
     */
    public function abs(): static
    {
        $this->result = (string)abs((float)$this->result);
        return $this;
    }

    public function __toString() {
        return (string)$this->result;
    }

}
