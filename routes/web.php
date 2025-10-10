<?php

use App\Helpers\AgendaHelper;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GetAgendaByDateController;

// Route::statamic('example', 'example-view', [
//    'title' => 'Example'
// ]);

Route::permanentRedirect('/', '/en');
// Route::permanentRedirect('/ar', '/en');


Route::get('/{locale}/agenda/{agenda}/{date}', GetAgendaByDateController::class)->name('show_agenda_by_date');

