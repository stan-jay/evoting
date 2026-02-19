<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('elections:update-status')
    ->everyMinute();

Schedule::command('app:backup-db --retention=14')
    ->dailyAt('02:00')
    ->withoutOverlapping();

Schedule::command('app:env-sanity-check')
    ->dailyAt('01:30')
    ->withoutOverlapping();
