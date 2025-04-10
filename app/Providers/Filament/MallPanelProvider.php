<?php

namespace App\Providers\Filament;

use App\Filament\Admin\Pages\Auth\CustomEditProfile;
use App\Filament\Admin\Pages\Auth\CustomLogin;
use App\Filament\Mall\Resources\Goods\MallGoodsCategoryResource;
use CharrafiMed\GlobalSearchModal\GlobalSearchModalPlugin;
use Exception;
use Filament\Enums\ThemeMode;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class MallPanelProvider extends PanelProvider
{
    /**
     * @throws Exception
     */
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('mall')
            ->path('mall')
            ->login(CustomLogin::class) // 登录页配置
            ->colors([
                'primary' => Color::Green,
            ])
            ->pages([
                Pages\Dashboard::class,
            ])
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                GlobalSearchModalPlugin::make()
            ])
            ->globalSearch() // 开启全局搜索
            ->breadcrumbs(false) // 禁用面包屑
            ->profile(CustomEditProfile::class, false) // 个人资料页配置
            ->defaultThemeMode(ThemeMode::Light) // 默认主题
            ->databaseNotifications() // 开启数据库通知
            ->discoverResources(in: app_path('Filament/Mall/Resources'), for: 'App\\Filament\\Mall\\Resources')
            ->discoverPages(in: app_path('Filament/Mall/Pages'), for: 'App\\Filament\\Mall\\Pages')
            ->discoverWidgets(in: app_path('Filament/Mall/Widgets'), for: 'App\\Filament\\Mall\\Widgets')
            ->discoverClusters(in: app_path('Filament/Mall/Clusters'), for: 'App\\Filament\\Mall\\Clusters')
            ->spa()
            ->spaUrlExceptions(fn (): array => [
                MallGoodsCategoryResource::getUrl(), // 商品分类使用了Tree组件，需要禁用spa导航才能正常拖拽
            ]);
    }
}
