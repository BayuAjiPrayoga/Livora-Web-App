<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule automatic booking status updates
Schedule::command('booking:update-status')
    ->dailyAt('00:01')
    ->timezone('Asia/Jakarta')
    ->runInBackground();
