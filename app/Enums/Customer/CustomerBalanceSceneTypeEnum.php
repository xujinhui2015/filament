<?php

namespace App\Enums\Customer;

use App\Support\Traits\EnumTrait;
use Filament\Support\Contracts\HasLabel;
use UnhandledMatchError;

enum CustomerBalanceSceneTypeEnum: int implements HasLabel
{
    use EnumTrait;

    case Unknown = 0;
    case MallOrder = 1;
    case MallOrderRefund = 2;

    public function getLabel(): string
    {
        return match ($this) {
            self::Unknown => '未知',
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
                self::Unknown,self::MallOrderRefund => true,
                self::MallOrder => false,
            };
        } catch (UnhandledMatchError) {
            throw new UnhandledMatchError('余额未设置正负值符号');
        }

    }
}
