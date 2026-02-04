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

// Route::statamic('example', 'example-view', [
//    'title' => 'Example'
// ]);

// Route::permanentRedirect('/', '/en');
// Route::permanentRedirect('/ar', '/en');


Route::get('/{locale}/agenda/{agenda}/{date}', GetAgendaByDateController::class)->name('show_agenda_by_date');

// redirect all /ar starting routes to homepage (including /ar)
// Route::permanentRedirect('/ar/{any?}', '/en')->where('any', '.*');
