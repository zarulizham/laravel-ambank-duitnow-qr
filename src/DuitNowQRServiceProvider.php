<?php

namespace ZarulIzham\DuitNowQR;

use Illuminate\Routing\Router;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use ZarulIzham\DuitNowQR\Http\Middleware\EnsureDuitNowQRDashboardAuthorized;

class DuitNowQRServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */

        $package
            ->hasCommand('ZarulIzham\DuitNowQR\Commands\DuitNowQRCommand')
            ->name('laravel-duitnow-qr')
            ->hasConfigFile('duitnowqr')
            ->hasViews()
            ->hasRoute('web')
            ->hasMigrations('create_duitnow_qr_transactions', 'create_duitnow_qr_payments', 'create_duitnow_qr_requeries');
    }

    public function packageBooted(): void
    {
        $this->app->make(Router::class)->aliasMiddleware(
            'duitnowqr.dashboard.auth',
            EnsureDuitNowQRDashboardAuthorized::class
        );
    }
}
