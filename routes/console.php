<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule::command('app:test-scheduler')
//     ->everyMinute()
//     ->onFailure(function ($output) {
//         report('Falhou o teste do scheduler: ' . (string) $output);
//     });
