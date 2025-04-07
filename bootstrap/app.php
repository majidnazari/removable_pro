<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\GraphQLStatusCodeMiddleware; 
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withSchedule(function (Schedule $schedule) {
        // $schedule->call(new DeleteRecentUsers)->daily();
        $schedule->command('familyboards:delete-old')->everyMinute()->runInBackground();
        //$schedule->call(Log::info("test log for schedules!"))->everyMinute();
    })
    ->withMiddleware(function (Middleware $middleware) {
        // 
        $middleware->append(\Illuminate\Http\Middleware\HandleCors::class);
        $middleware->append(GraphQLStatusCodeMiddleware::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
