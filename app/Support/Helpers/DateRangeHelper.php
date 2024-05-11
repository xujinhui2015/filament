<?php

namespace App\Support\Helpers;

use Carbon\Carbon;

/**
 * 日期处理类
 */
class DateRangeHelper
{
    const FORMAT_TYPE_DATETIME = 'datetime';
    const FORMAT_TYPE_TIMESTAMP = 'timestamp';

    private function __construct(
        private readonly Carbon $calcDate,
        private readonly string $formatType,
    )
    {
    }

    /**
     * 通过静态调用方法
     * @param Carbon|null $calcDate 计算时间
     * @param string $formatType 格式化类型
     * @return static
     */
    public static function begin(Carbon $calcDate = null, string $formatType = self::FORMAT_TYPE_DATETIME): static
    {
        return new self($calcDate ?? Carbon::now(), $formatType);
    }

    /**
     * 格式化时间
     * DateRangeHelper::begin()->get('to_day')
     * @param Carbon $carbon
     * @return float|int|string
     */
    private function format(Carbon $carbon): float|int|string
    {
        return match ($this->formatType) {
            self::FORMAT_TYPE_DATETIME => $carbon->toDateTimeString(),
            self::FORMAT_TYPE_TIMESTAMP => $carbon->timestamp,
        };
    }

    /**
     * 根据类型返回相应的时间
     */
    public function get($type): ?array
    {
        $function = lcfirst(str_replace('_', '', ucwords($type, '_')));
        if (!method_exists($this, $function)) {
            return null;
        }
        return $this->$function();
    }

    /**
     * 获取今日
     */
    public function toDay(): array
    {
        return [
            $this->format($this->calcDate->startOfDay()),
            $this->format($this->calcDate->endOfDay()),
        ];
    }

    /**
     * 获取昨天
     */
    public function yestDay(): array
    {
        $yestDay = $this->calcDate->subDay();
        return [
            $this->format($yestDay->startOfDay()),
            $this->format($yestDay->endOfDay()),
        ];
    }

    /**
     * 获取本周
     */
    public function toWeek(): array
    {
        return [
            $this->format($this->calcDate->startOfWeek()),
            $this->format($this->calcDate->endOfWeek()),
        ];
    }

    /**
     * 获取上周
     */
    public function yestWeek(): array
    {
        $yestWeek = $this->calcDate->subWeek();
        return [
            $this->format($yestWeek->startOfWeek()),
            $this->format($yestWeek->endOfWeek()),
        ];
    }

    /**
     * 获取本月
     */
    public function toMonth(): array
    {
        return [
            $this->format($this->calcDate->startOfMonth()),
            $this->format($this->calcDate->endOfMonth()),
        ];
    }

    /**
     * 获取上月
     */
    public function yestMonth(): array
    {
        $yestMonth = $this->calcDate->subMonthNoOverflow();
        return [
            $this->format($yestMonth->startOfMonth()),
            $this->format($yestMonth->endOfMonth()),
        ];
    }

    /**
     * 获取今年
     */
    public function toYear(): array
    {
        return [
            $this->format($this->calcDate->startOfYear()),
            $this->format($this->calcDate->endOfYear()),
        ];
    }

    /**
     * 获取去年
     */
    public function yestYear(): array
    {
        $yestYear = $this->calcDate->subYearNoOverflow();
        return [
            $this->format($yestYear->startOfYear()),
            $this->format($yestYear->endOfYear()),
        ];
    }

    /**
     * 获取近N天的数据
     */
    public function nearlyDay($subDay): array
    {
        return [
            $this->format($this->calcDate->clone()->subDays($subDay)->startOfDay()),
            $this->format($this->calcDate->endOfDay()),
        ];
    }


}
