<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule automatic archiving of overdue requests and trips
Schedule::command('archive:overdue')->daily();

// Check for delayed trips every 5 minutes
Schedule::job(new \App\Jobs\DetectDelayedTrips)->everyFiveMinutes();