<?php

namespace App\Enums\Customer;

use App\Support\Traits\EnumTrait;
use Filament\Support\Contracts\HasLabel;
use UnhandledMatchError;

enum CustomerBalanceSceneTypeEnum: int implements HasLabel
{
    use EnumTrait;

    case Increase = 0;
    case Deducted = 1;
    case MallOrder = 2;
    case MallOrderRefund = 3;

    public function getLabel(): string
    {
        return match ($this) {
            self::Increase => '系统增加',
            self::Deducted => '系统扣除',
            self::MallOrder => '商城订单',
            self::MallOrderRefund => '商城退款单',
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
                self::Increase, self::MallOrderRefund => true,
                self::Deducted, self::MallOrder => false,
            };
        } catch (UnhandledMatchError) {
            throw new UnhandledMatchError('余额未设置正负值符号');
        }

    }
}
