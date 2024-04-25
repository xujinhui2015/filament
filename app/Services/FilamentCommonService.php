<?php

namespace App\Services;

use Exception;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FilamentCommonService
{
    /**
     * 获取过滤器的时间范围字段
     *
     * @throws Exception
     */
    public static function getFilterDateRange($datetimeFieldName): Filter
    {
        $fromKey = $datetimeFieldName . '_from';
        $untilKey = $datetimeFieldName . '_until';
        return Filter::make($datetimeFieldName)
            ->form([
                DatePicker::make($fromKey)->label('起始时间'),
                DatePicker::make($untilKey)->label('结束时间'),
            ])
            ->query(function (Builder $query, array $data) use ($fromKey, $untilKey, $datetimeFieldName): Builder {
                return $query
                    ->when(
                        $data[$fromKey],
                        fn (Builder $query, $date): Builder => $query->whereDate($datetimeFieldName, '>=', $date),
                    )
                    ->when(
                        $data[$untilKey],
                        fn (Builder $query, $date): Builder => $query->whereDate($datetimeFieldName, '<=', $date),
                    );
            });
    }
}
