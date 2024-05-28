<?php

namespace App\Providers;

use App\Models\Customer\Customer;
use App\Models\Mall\MallOrder;
use App\Observers\Mall\MallOrderObserver;
use App\Policies\Customer\CustomerPolicy;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Opcodes\LogViewer\Facades\LogViewer;
use Rawilk\FilamentQuill\Enums\ToolbarButton;
use Rawilk\FilamentQuill\Filament\Forms\Components\QuillEditor;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Table::configureUsing(function (Table $table): void {
            // 默认分页配置
            $table
                ->paginationPageOptions([10, 25, 50, 100]);
        });

        // 访问日志限制权限
        LogViewer::auth(function ($request) {
            return $request->user()
                && $request->user()->id == 1;
        });

        // 富文本默认配置
        QuillEditor::configureUsing(function (QuillEditor $quillEditor) {
            // 默认禁用字体
            $quillEditor->disableToolbarButtons([
                ToolbarButton::Font,
            ]);
        });

        // 商城模块
        MallOrder::observe(MallOrderObserver::class);

        // 自动注册策略
        Gate::guessPolicyNamesUsing(function (string $modelClass) {
            return Str::replace('App\\Models', 'App\\Policies', $modelClass) . 'Policy';
        });

    }
}
