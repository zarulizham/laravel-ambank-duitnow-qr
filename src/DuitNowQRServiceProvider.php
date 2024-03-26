<?php

namespace ZarulIzham\DuitNowQR;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

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
            ->hasMigrations('create_duitnow_qr_transactions', 'create_duitnow_qr_payments');
    }
}
