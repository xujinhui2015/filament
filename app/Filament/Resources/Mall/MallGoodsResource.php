<?php

namespace App\Filament\Resources\Mall;

use App\Enums\IsYesOrNoEnum;
use App\Filament\Resources\Mall\MallGoodsResource\Pages;
use App\Models\Mall\MallAttr;
use App\Models\Mall\MallAttrValue;
use App\Models\Mall\MallGoods;
use App\Models\Mall\MallGoodsAttrValue;
use App\Support\Helpers\FilePathHelper;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use CodeWithDennis\FilamentSelectTree\SelectTree;
use Exception;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Rawilk\FilamentQuill\Filament\Forms\Components\QuillEditor;

class MallGoodsResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = MallGoods::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = '商品管理';
    protected static ?string $modelLabel = '商品';
    protected static ?string $navigationGroup = '商城';

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
            'reorder',
            'restore',
            'restore_any',
        ];
    }

    public static function form(Form $form): Form
    {
        if ($form->getOperation() == 'create') {
            // 创建表单
            return $form
                ->schema([
                    Forms\Components\Wizard::make([
                        Forms\Components\Wizard\Step::make('basic')
                            ->schema([
                                Forms\Components\TextInput::make('goods_sn')
                                    ->required()
                                    ->unique()
                                    ->maxLength(100)
                                    ->label('商品代码'),
                                SelectTree::make('goods_category_id')
                                    ->required()
                                    ->relationship('category', 'title', 'parent_id')
                                    ->emptyLabel('没有商品分类')
                                    ->searchable()
                                    ->parentNullValue(0)
                                    ->label('商品分类'),
                                Forms\Components\TextInput::make('goods_name')
                                    ->required()
                                    ->maxLength(100)
                                    ->label('商品名称'),
                                Forms\Components\TextInput::make('subtitle')
                                    ->required()
                                    ->maxLength(100)
                                    ->label('商品副标题'),
                                Forms\Components\Select::make('attr')
                                    ->options(MallAttr::options())
                                    ->multiple()
                                    ->required()
                                    ->minItems(1)
                                    ->maxItems(2)
                                    ->live()
                                    ->afterStateUpdated(fn(Forms\Components\Select $component) => $component
                                        ->getContainer()
                                        ->getComponent('attrValue')
                                        ->getChildComponentContainer()
                                        ->fill())
                                    ->label('商品规格'),
                                Forms\Components\Grid::make()
                                    ->schema(function (Forms\Get $get): array {
                                        if (!$get('attr')) {
                                            return [];
                                        }
                                        return MallAttr::query()
                                            ->whereIn('id', $get('attr'))
                                            ->with([
                                                'value' => function (HasMany $query) {
                                                    $query->where('is_disabled', false);
                                                }
                                            ])
                                            ->get()
                                            ->map(function (MallAttr $mallAttr) {
                                                return Forms\Components\Select::make('attr_value_' . $mallAttr->id)
                                                    ->multiple()
                                                    ->required()
                                                    ->minItems(1)
                                                    ->options($mallAttr->value->pluck('attr_value_name', 'id')->toArray())
                                                    ->label($mallAttr->attr_name);
                                            })->toArray();
                                    })->key('attrValue'),
                            ])
                            ->label('基本信息'),
                        Forms\Components\Wizard\Step::make('price')
                            ->schema(function (Forms\Get $get): array {
                                // 处理无限极分类

                                // 获取所有接收的规格属性
                                $attr = $get('attr');
                                $attrArray = collect($attr)->map(function ($attrId) use ($get) {
                                    return $get('attr_value_' . $attrId);
                                });
                                // 算出所有可能性
                                $attrValueAll = [[]];
                                foreach ($attrArray as $values) {
                                    $temp = [];
                                    foreach ($attrValueAll as $combination) {
                                        foreach ($values as $value) {
                                            $temp[] = array_merge($combination, [$value]);
                                        }
                                    }
                                    $attrValueAll = $temp;
                                }

                                return collect($attrValueAll)->map(function ($attrValueIds) {

                                    $specKey = implode('-', $attrValueIds);
                                    $spec = MallAttrValue::query()
                                        ->whereIn('id', $attrValueIds)
                                        ->pluck('attr_value_name')
                                        ->implode('-');

                                    return Forms\Components\Fieldset::make($spec)
                                        ->schema([
                                            Forms\Components\FileUpload::make('sku_img:' . $specKey)
                                                ->imageEditor()
                                                ->imageEditorViewportWidth('100')
                                                ->imageEditorViewportHeight('100')
                                                ->getUploadedFileNameForStorageUsing(FilePathHelper::uploadUsing(FilePathHelper::MALL_GOODS))
                                                ->columnSpanFull()
                                                ->label('规格图片'),
                                            Forms\Components\TextInput::make('price:' . $specKey)
                                                ->required()
                                                ->prefix('￥')
                                                ->label('商品价格'),
                                            Forms\Components\TextInput::make('stock:' . $specKey)
                                                ->required()
                                                ->label('商品库存'),
                                        ]);
                                })->toArray();
                            })->label('价格设置'),
                        Forms\Components\Wizard\Step::make('detail')
                            ->schema([
                                Forms\Components\FileUpload::make('main_img')
                                    ->required()
                                    ->image()
                                    ->imageEditor()
                                    ->getUploadedFileNameForStorageUsing(FilePathHelper::uploadUsing(FilePathHelper::MALL_GOODS))
                                    ->columnSpanFull()
                                    ->label('商品主图'),
                                Forms\Components\FileUpload::make('images')
                                    ->required()
                                    ->image()
                                    ->imageEditor()
                                    ->getUploadedFileNameForStorageUsing(FilePathHelper::uploadUsing(FilePathHelper::MALL_GOODS))
                                    ->columnSpanFull()
                                    ->multiple()
                                    ->label('商品轮播图'),
                                QuillEditor::make('content')
                                    ->required()
                                    ->fileAttachmentsDirectory(FilePathHelper::uploadDir(FilePathHelper::MALL_GOODS))
                                    ->columnSpanFull()
                                    ->label('内容'),
                            ])->label('商品详情')
                    ])->columnSpanFull()->submitAction(new HtmlString(Blade::render(<<<BLADE
    <x-filament::button
        type="submit"
        size="sm"
    >
        创建商品
    </x-filament::button>
BLADE
                    ))),
                ]);
        } else {
            // 编辑
            return $form
                ->schema([
                    Forms\Components\TextInput::make('goods_sn')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(100)
                        ->label('商品代码'),
                    SelectTree::make('goods_category_id')
                        ->required()
                        ->relationship('category', 'title', 'parent_id')
                        ->emptyLabel('没有商品分类')
                        ->searchable()
                        ->parentNullValue(0)
                        ->label('商品分类'),
                    Forms\Components\TextInput::make('goods_name')
                        ->required()
                        ->maxLength(100)
                        ->label('商品名称'),
                    Forms\Components\TextInput::make('subtitle')
                        ->required()
                        ->maxLength(100)
                        ->label('商品副标题'),
                    Forms\Components\FileUpload::make('main_img')
                        ->required()
                        ->image()
                        ->imageEditor()
                        ->getUploadedFileNameForStorageUsing(FilePathHelper::uploadUsing(FilePathHelper::MALL_GOODS))
                        ->columnSpanFull()
                        ->label('商品主图'),
                    Forms\Components\FileUpload::make('images')
                        ->required()
                        ->image()
                        ->imageEditor()
                        ->getUploadedFileNameForStorageUsing(FilePathHelper::uploadUsing(FilePathHelper::MALL_GOODS))
                        ->columnSpanFull()
                        ->multiple()
                        ->label('商品轮播图'),
                    Forms\Components\Repeater::make('attr')
                        ->relationship()
                        ->schema([
                            Forms\Components\Repeater::make('value')
                                ->relationship()
                                ->schema([
                                    Forms\Components\Placeholder::make('attr_value_name')
                                        ->content(fn (MallGoodsAttrValue $record): string => $record->attr_value_name)
                                        ->label(''),
                                    Forms\Components\Radio::make('is_disabled')
                                        ->options(IsYesOrNoEnum::options())
                                        ->inline()
                                        ->label('是否禁用')
                                ])
                                ->deletable(false)
                                ->addable(false)
                                ->orderColumn()
                                ->label('')

                        ])
                        ->deletable(false)
                        ->addable(false)
                        ->grid()
                        ->columnSpanFull()
                        ->collapsed()
                        ->itemLabel(fn(array $state): string => $state['attr_name'])
                        ->label('商品规格'),
                    Forms\Components\Repeater::make('sku')
                        ->relationship()
                        ->schema([
                            Forms\Components\FileUpload::make('sku_img')
                                ->imageEditor()
                                ->imageEditorViewportWidth('100')
                                ->imageEditorViewportHeight('100')
                                ->getUploadedFileNameForStorageUsing(FilePathHelper::uploadUsing(FilePathHelper::MALL_GOODS))
                                ->columnSpanFull()
                                ->label(''),
                            Forms\Components\TextInput::make('price')
                                ->required()
                                ->prefix('￥')
                                ->label('商品价格'),
                            Forms\Components\TextInput::make('stock')
                                ->required()
                                ->label('商品库存'),
                        ])
                        ->deletable(false)
                        ->addable(false)
                        ->grid(4)
                        ->columnSpanFull()
                        ->itemLabel(fn(array $state): string => MallAttrValue::query()
                            ->whereIn('id', explode('-', $state['spec']))
                            ->pluck('attr_value_name')
                            ->implode('-'))
                        ->label('商品SKU'),

                    QuillEditor::make('content')
                        ->required()
                        ->fileAttachmentsDirectory(FilePathHelper::uploadDir(FilePathHelper::MALL_GOODS))
                        ->columnSpanFull()
                        ->label('内容'),
                ]);
        }


    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('goods_sn')
                    ->searchable()
                    ->label('商品编号'),
                Tables\Columns\TextColumn::make('category.title')
                    ->numeric()
                    ->label('商品分类'),
                Tables\Columns\TextColumn::make('goods_name')
                    ->searchable()
                    ->label('商品名称'),
                Tables\Columns\TextColumn::make('subtitle')
                    ->searchable()
                    ->label('商品副标题'),
                Tables\Columns\ImageColumn::make('main_img')
                    ->label('商品主图'),
                Tables\Columns\ToggleColumn::make('is_sale')
                    ->label('上架状态'),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('删除时间'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('创建时间'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('更新时间'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
                Tables\Actions\BulkAction::make('批量上架')
                    ->button()
                    ->action(function (Collection $records) {
                        $records->each(function (MallGoods $mallGoods) {
                            $mallGoods->update([
                                'is_sale' => true,
                            ]);
                        });
                    }),

            ])
            ->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMallGoods::route('/'),
            'create' => Pages\CreateMallGoods::route('/create'),
            'edit' => Pages\EditMallGoods::route('/{record}/edit'),
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
        return MallGoods::query()->count();
    }
}
