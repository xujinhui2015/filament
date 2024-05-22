<?php

namespace App\Support\Helpers;

use Carbon\Carbon;
use Closure;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

/**
 * 文件路径
 */
class FilePathHelper
{
    const DEFAULT = 'default';
    const AVATAR = 'avatar'; // 头像相关
    const MALL_GOODS = 'mall_goods'; // 商品图

    /**
     * 上传设置
     */
    public static function uploadUsing($prepend = self::DEFAULT): Closure
    {
        $nowMonthDate = Carbon::now()->rawFormat('Y-m');

        return function (TemporaryUploadedFile $file) use ($nowMonthDate, $prepend) {
            return implode('/', [
                $prepend,
                $nowMonthDate,
                strtolower(Str::random(2)),
                now()->timestamp . '-' . $file->getClientOriginalName(),
            ]);
        };
    }

    /**
     * 获取上传目录
     */
    public static function uploadDir($prepend = self::DEFAULT): string
    {
        return implode('/', [
            $prepend,
            Carbon::now()->rawFormat('Y-m'),
            strtolower(Str::random(2)),
        ]);
    }
}
