<?php

namespace App\Filament\Mall\Clusters\MallGoodsCluster\Resources;

use App\Filament\Mall\Clusters\MallGoodsCluster;
use App\Filament\Mall\Clusters\MallGoodsCluster\Resources\MallRefundAddressResource\Pages\ManageMallRefundAddresses;
use App\Filament\Mall\Resources\MallResource;
use App\Models\Mall\MallRefundAddress;
use App\Services\Filament\FilamentService;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MallRefundAddressResource extends MallResource implements HasShieldPermissions
{
    protected static ?string $model = MallRefundAddress::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = MallGoodsCluster::class;

    protected static ?string $navigationLabel = '退货地址管理';
    protected static ?string $modelLabel = '退货地址';

    public static function getPermissionPrefixes(): array
    {
        return [
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
            'force_delete',
            'force_delete_any',
            'restore',
            'restore_any',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required()->label('退货人名称'),
                Forms\Components\TextInput::make('phone')->required()->label('退货人电话'),
                FilamentService::getFormArea(),
                Forms\Components\TextInput::make('address')->columnSpanFull()->required()->label('详细地址'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->label('退货人名称'),
                Tables\Columns\TextColumn::make('phone')->searchable()->label('退货人电话'),
                Tables\Columns\TextColumn::make('full_address')->searchable(
                    query: function (Builder $query, string $search) {
                        $query->where(function (Builder $query) use ($search) {
                            $query->where('province', 'like', "%$search%");
                            $query->orWhere('city', 'like', "%$search%");
                            $query->orWhere('district', 'like', "%$search%");
                            $query->orWhere('address', 'like', "%$search%");
                        });
                    }
                )->label('退货地址'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageMallRefundAddresses::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return app(self::$model)->count();
    }
}
