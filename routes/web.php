<?php

use App\Helpers\AgendaHelper;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GetAgendaByDateController;

// Health check endpoint for Docker/Coolify
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString()
    ]);
});

// Debug endpoint - REMOVE THIS IN PRODUCTION!
Route::get('/debug-logs', function () {
    $logPath = storage_path('logs/laravel.log');
    
    if (file_exists($logPath)) {
        $logs = file_get_contents($logPath);
        return response('<pre>' . htmlspecialchars($logs) . '</pre>');
    }
    
    return response()->json([
        'message' => 'No logs found',
        'log_path' => $logPath,
        'storage_writable' => is_writable(storage_path('logs')),
        'db_exists' => file_exists(database_path('database.sqlite')),
        'db_writable' => is_writable(database_path('database.sqlite')),
        'env' => [
            'APP_ENV' => env('APP_ENV'),
            'APP_DEBUG' => env('APP_DEBUG'),
            'DB_CONNECTION' => env('DB_CONNECTION'),
            'DB_DATABASE' => env('DB_DATABASE'),
        ]
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
