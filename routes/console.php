<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Автоматичний імпорт новин з RSS-стрічок (за наявності планувальника — щогодини)
Schedule::command('news:fetch')->hourly();
