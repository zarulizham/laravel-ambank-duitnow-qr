# Malaysia's National QR Payment repository for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/zarulizham/laravel-duitnow-qr.svg?style=flat-square)](https://packagist.org/packages/zarulizham/laravel-duitnow-qr)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/zarulizham/laravel-duitnow-qr/run-tests?label=tests)](https://github.com/zarulizham/laravel-duitnow-qr/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/zarulizham/laravel-duitnow-qr/Check%20&%20fix%20styling?label=code%20style)](https://github.com/zarulizham/laravel-duitnow-qr/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/zarulizham/laravel-duitnow-qr.svg?style=flat-square)](https://packagist.org/packages/zarulizham/laravel-duitnow-qr)

## Installation

You can install the package via composer:

```bash
composer require zarulizham/laravel-duitnow-qr
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="laravel-duitnow-qr-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-duitnow-qr-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="laravel-duitnow-qr-views"
```

## Usage

```php
$duitNowQR = new ZarulIzham\DuitNowQR();
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

-   [Zarul Zubir](https://github.com/zarulizham)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
