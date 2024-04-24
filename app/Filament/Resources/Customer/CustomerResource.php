<?php

namespace App\Filament\Resources\Customer;

use App\Filament\Resources\Customer\CustomerResource\Pages;
use App\Models\Customer\Customer;
use Exception;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationLabel = '会员管理';

    protected static ?string $navigationGroup = '会员';

    protected static ?string $modelLabel = '会员';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nickname')->searchable()->toggleable()->label('名称'),
                Tables\Columns\ImageColumn::make('avatar_url')
                    ->circular()
                    ->toggleable()
                    ->label('头像'),
                Tables\Columns\TextColumn::make('balance')
                    ->prefix('￥')
                    ->label('余额')
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('points')->toggleable()->label('积分'),
                Tables\Columns\TextColumn::make('created_at')->toggleable(true, true)->label('注册时间'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
//                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ManageCustomers::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
