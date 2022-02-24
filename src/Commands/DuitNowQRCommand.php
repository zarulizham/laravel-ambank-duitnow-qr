<?php

namespace ZarulIzham\DuitNowQR\Commands;

use Illuminate\Console\Command;

class DuitNowQRCommand extends Command
{
    public $signature = 'laravel-duitnow-qr';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
