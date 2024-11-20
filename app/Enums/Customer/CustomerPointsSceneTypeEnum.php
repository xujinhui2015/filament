<?php

namespace App\Enums\Customer;

use App\Support\Traits\EnumTrait;
use Filament\Support\Contracts\HasLabel;
use UnhandledMatchError;

enum CustomerPointsSceneTypeEnum: int implements HasLabel
{
    use EnumTrait;

    case Increase = 0;
    case Deducted = 1;

    public function getLabel(): string
    {
        return match ($this) {
            self::Increase => '系统增加',
            self::Deducted => '系统扣除',
        };
    }

    /**
     * 正负值符号配置
     * @return bool true表示增加,false表示减少
     */
    public function getSign(): bool
    {
        try {
            return match ($this) {
                self::Increase=> true,
                self::Deducted=> false,
            };
        } catch (UnhandledMatchError) {
            throw new UnhandledMatchError('积分未设置正负值符号');
        }
    }
}
