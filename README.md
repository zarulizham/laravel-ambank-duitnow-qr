# AmBank Malaysia's National QR Payment repository for Laravel


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

This is the contents of the `.env` file:

```env
DUITNOW_QR_CLIENT_ID=
DUITNOW_QR_CLIENT_SECRET=
DUITNOW_QR_CHANNEL_TOKEN=
DUITNOW_QR_PREFIX_ID=
DUITNOW_QR_QR_ID=
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="laravel-duitnow-qr-views"
```

## Usage

```php
$duitNowQR = new ZarulIzham\DuitNowQR();
```

### Dashboard SPA (Transactions & Payments)

This package now provides a gated dashboard with Vue.js components and Bootstrap 5.3.

- Transactions page: list all transactions and open a transaction details page (with related payments).
- Payments page: list all payments with optional filters by `biz_id` or `end_id`.

Default URL:

```text
/duitnow-qr/dashboard
```

Set your access gate (for example in `AppServiceProvider::boot`):

```php
use DuitNowQR;

DuitNowQR::auth(function ($request) {
	return $request->user()
		&& $request->user()->role('Programmer');
});
```

> Dashboard API endpoints are protected (authenticated users only). If `DuitNowQR::auth(...)` is configured, it is additionally enforced for API access.

Dashboard route settings are configurable in `config/duitnowqr.php`:

```php
'dashboard' => [
	'path' => env('DUITNOW_QR_DASHBOARD_PATH', 'duitnow-qr/dashboard'),
	'middleware' => ['web'],
],
```


## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
