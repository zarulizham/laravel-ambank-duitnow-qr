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
            ->name('laravel-duitnow-qr')
            ->hasRoute('web')
            ->hasConfigFile('duitnowqr')
            ->hasMigrations('create_duitnow_qr_info', 'create_duitnow_qr_payments');
    }
}