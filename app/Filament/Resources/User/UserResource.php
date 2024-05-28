<?php

namespace App\Filament\Resources\User;

use App\Filament\Resources\User\UserResource\Pages;
use App\Models\User;
use App\Support\Helpers\FilePathHelper;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UserResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = '系统用户';

    protected static ?string $navigationGroup = '权限管理';

    protected static ?string $modelLabel = '系统用户';

    protected static ?int $navigationSort = 1;

    public static function getPermissionPrefixes(): array
    {
        return [
            'view_any',
            'create',
            'update',
            'reorder',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('avatar_url')
                    ->avatar()
                    ->imageEditor()
                    ->getUploadedFileNameForStorageUsing(FilePathHelper::uploadUsing(FilePathHelper::AVATAR))
                    ->columnSpanFull()
                    ->label('头像'),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('用户昵称'),
                Forms\Components\TextInput::make('phone')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(32)
                    ->label('手机号'),
                Forms\Components\TextInput::make('email')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->label('邮箱'),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->helperText('空表示不修改密码')
                    ->autocomplete('new-password')
                    ->revealable()
                    ->label('密码'),
                Forms\Components\Select::make('roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->label('所属角色'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->label('用户昵称'),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable()
                    ->label('登录手机号'),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->label('邮箱'),
                Tables\Columns\ImageColumn::make('avatar_url')
                    ->circular()
                    ->label('头像'),
                Tables\Columns\TextColumn::make('created_at')
                    ->sortable()
                    ->toggleable(true, true)
                    ->label('注册时间'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('更新时间'),
                Tables\Columns\TextColumn::make('roles.name')
                    ->listWithLineBreaks()->badge()
                    ->separator()
                    ->label('所属角色'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageUsers::route('/'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return User::query()->count();
    }
}
