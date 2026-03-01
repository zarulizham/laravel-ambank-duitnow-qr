<?php

use Illuminate\Support\Facades\Route;
use ZarulIzham\DuitNowQR\Http\Controllers\DashboardController;

$path = trim(config('duitnowqr.dashboard.path', 'duitnow-qr/dashboard'), '/');
$middleware = config('duitnowqr.dashboard.middleware', ['web']);
$middleware = array_merge((array) $middleware, ['duitnowqr.dashboard.auth']);

Route::prefix($path)
    ->middleware($middleware)
    ->name('duitnowqr.dashboard.')
    ->group(function () {
        Route::get('/assets/{file}', [DashboardController::class, 'asset'])
            ->where('file', '.*')
            ->name('assets');

        Route::get('/', [DashboardController::class, 'index'])->name('index');

        Route::prefix('api')
            ->middleware('duitnowqr.dashboard.auth:api')
            ->group(function () {
                Route::get('/transactions', [DashboardController::class, 'transactions'])->name('transactions.index');
                Route::get('/transactions/{transaction}', [DashboardController::class, 'showTransaction'])->name('transactions.show');
                Route::post('/transactions/{transaction}/mock-payment', [DashboardController::class, 'mockPayment'])->name('transactions.mock-payment');
                Route::get('/payments', [DashboardController::class, 'payments'])->name('payments.index');
            });
    });
