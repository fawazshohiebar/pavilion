<?php

use App\Helpers\AgendaHelper;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GetAgendaByDateController;

// IMPORTANT: These routes use the action prefix to avoid Statamic route conflicts
// Debug endpoint - REMOVE THIS IN PRODUCTION!
Route::get('!/debug-info', function () {
    $logPath = storage_path('logs/laravel.log');
    $logContent = null;
    
    if (file_exists($logPath)) {
        // Get last 500 lines of log
        $logContent = shell_exec("tail -500 $logPath");
    }
    
    return response()->json([
        'app_running' => true,
        'message' => $logContent ? 'Logs found' : 'No logs found',
        'log_path' => $logPath,
        'log_exists' => file_exists($logPath),
        'log_preview' => $logContent ? substr($logContent, -2000) : null,
        'storage_writable' => is_writable(storage_path('logs')),
        'db_exists' => file_exists(database_path('database.sqlite')),
        'db_path' => database_path('database.sqlite'),
        'db_writable' => file_exists(database_path('database.sqlite')) ? is_writable(database_path('database.sqlite')) : false,
        'php_version' => PHP_VERSION,
        'laravel_version' => app()->version(),
        'env' => [
            'APP_ENV' => env('APP_ENV'),
            'APP_DEBUG' => env('APP_DEBUG'),
            'DB_CONNECTION' => env('DB_CONNECTION'),
            'DB_DATABASE' => env('DB_DATABASE'),
        ],
        'paths' => [
            'base' => base_path(),
            'storage' => storage_path(),
            'database' => database_path(),
        ]
    ], 200, [], JSON_PRETTY_PRINT);
});

// Health check endpoint for Docker/Coolify
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString()
    ]);
});

// Route::statamic('example', 'example-view', [
//    'title' => 'Example'
// ]);

// Route::permanentRedirect('/', '/en');
// Route::permanentRedirect('/ar', '/en');


Route::get('/{locale}/agenda/{agenda}/{date}', GetAgendaByDateController::class)->name('show_agenda_by_date');

// redirect all /ar starting routes to homepage (including /ar)
// Route::permanentRedirect('/ar/{any?}', '/en')->where('any', '.*');
