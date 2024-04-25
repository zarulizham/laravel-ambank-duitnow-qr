<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;

Route::middleware(['web'])->group(function () {
    $callbackPath = Config::get('duitnowqr.callback_path');

    Route::post($callbackPath, [Controller::class, 'callback'])->name('duitnowqr.payment.callback');
});