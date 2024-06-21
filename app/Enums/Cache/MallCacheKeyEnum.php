<?php

namespace App\Enums\Cache;

use App\Support\Traits\EnumCacheTrait;
use App\Support\Traits\EnumTrait;

/**
 * 缓存key
 */
enum MallCacheKeyEnum: string
{
    use EnumCacheTrait;

    case SkuSpec = 'sku:spec:%s'; // SKU的spec_text缓存

    public function prefix(): string
    {
        return 'mall';
    }

    public function expire(): int
    {
        return match ($this) {
            self::SkuSpec => 10,
        };
    }


}
