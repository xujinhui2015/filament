<?php

namespace App\Enums;

use App\Support\Traits\EnumTrait;
use Filament\Support\Contracts\HasLabel;

enum MakeOrderNoEnum: string implements HasLabel
{

    use EnumTrait;

    case MallOrder = 'M';
    case MallRefundOrder = 'MR';

    public function getLabel(): string
    {
        return match ($this) {
            self::MallOrder => '商城订单',
            self::MallRefundOrder => '商城退款订单',
        };
    }

    public function new(): string
    {
        $code = $this->value . date('YmdHis');
        while (strlen($code) < 16) $code .= rand(0, 9);
        return $code;
    }
}
