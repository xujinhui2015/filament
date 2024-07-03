<?php

namespace App\Services;

use App\Models\Common\Area;
use Exception;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class FilamentService
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

    /**
     * 获取表单省市区级联
     */
    public static function getFormArea(): Section
    {
        return Section::make()
            ->columns(3)
            ->schema([
                Select::make('province')
                    ->options(Area::query()->where('parent_id', 0)->pluck('name', 'name'))
                    ->live()
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        $set('city', null);
                        $set('district', null);
                    })
                    ->searchable()
                    ->label('省'),
                Select::make('city')
                    ->options(fn (Get $get) => Area::query()
                        ->where('parent_id', function (QueryBuilder $query) use ($get) {
                            $query->where('parent_id', 0)
                                ->where('name', $get('province'))
                                ->select('area_id')
                                ->from('area');
                        })
                        ->pluck('name', 'name'))
                    ->live()
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        $set('district', null);
                    })
                    ->searchable()
                    ->label('市'),
                Select::make('district')
                    ->live()
                    ->options(fn (Get $get) => Area::query()
                        ->where('parent_id', function (QueryBuilder $query) use ($get) {
                            $query->where('parent_id', function (QueryBuilder $query) use ($get) {
                                $query->where('parent_id', 0)
                                    ->where('name', $get('province'))
                                    ->select('area_id')
                                    ->from('area');
                            })
                                ->where('name', $get('city'))
                                ->select('area_id')
                                ->from('area');
                        })->pluck('name', 'name'))
                    ->searchable()
                    ->label('区'),
            ]);

    }
}
