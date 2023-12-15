<?php

namespace ZarulIzham\DuitNowQR\Commands;

use ZarulIzham\DuitNowQR\Services\TestScript\Case2_1;
use ZarulIzham\DuitNowQR\Services\TestScript\Case2_2;
use ZarulIzham\DuitNowQR\Services\TestScript\Case2_3;
use ZarulIzham\DuitNowQR\Services\TestScript\Case2_4;
use ZarulIzham\DuitNowQR\Services\TestScript\Case2_5;
use ZarulIzham\DuitNowQR\Services\TestScript\Case2_6;
use ZarulIzham\DuitNowQR\Services\TestScript\Case2_7;
use ZarulIzham\DuitNowQR\Services\TestScript\Case2_8;
use ZarulIzham\DuitNowQR\Services\TestScript\Case2_9;
use ZarulIzham\DuitNowQR\Services\TestScript\Case2_10;
use ZarulIzham\DuitNowQR\Services\TestScript\Case2_11;
use ZarulIzham\DuitNowQR\Services\TestScript\Case2_12;
use Illuminate\Console\Command;

class DuitNowQRCommand extends Command
{
    public $signature = 'dnqr:test-script';

    public $description = 'Generate qr test script';

    public function handle(): int
    {
        $this->comment('2.1 - Generating QR');
        $case2_1 = new Case2_1();
        $case2_1->run();

        $this->comment('2.2 - Generating QR');
        $case2_2 = new Case2_2();
        $case2_2->run();

        $this->comment('2.3 - Generating QR');
        $case2_3 = new Case2_3();
        $case2_3->run();

        $this->comment('2.4 - Generating QR');
        $case2_4 = new Case2_4();
        $case2_4->run();

        $this->comment('2.5 - Generating QR');
        $case2_5 = new Case2_5();
        $case2_5->run();

        $this->comment('2.6 - Generating QR');
        $case2_6 = new Case2_6();
        $case2_6->run();

        $this->comment('2.7 - Generating QR');
        $case2_7 = new Case2_7();
        $case2_7->run();

        $this->comment('2.8 - Generating QR');
        $case2_8 = new Case2_8();
        $case2_8->run();

        $this->comment('2.9 - Generating QR');
        $case2_9 = new Case2_9();
        $case2_9->run();

        $this->comment('2.10 - Generating QR');
        $case2_10 = new Case2_10();
        $case2_10->run();

        $this->comment('2.11 - Generating QR');
        $case2_11 = new Case2_11();
        $case2_11->run();

        $this->comment('2.12 - Generating QR');
        $case2_12 = new Case2_12();
        $case2_12->run();

        $this->comment('All Done');

        return self::SUCCESS;
    }
}
