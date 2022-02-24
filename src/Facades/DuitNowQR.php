<?php

namespace ZarulIzham\DuitNowQR\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \ZarulIzham\DuitNowQR\DuitNowQR
 */
class DuitNowQR extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \ZarulIzham\DuitNowQR\DuitNowQR::class;
    }
}
