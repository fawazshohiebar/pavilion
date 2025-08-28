<?php

namespace App\Http\Controllers;

use App\Helpers\AgendaHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Statamic\Facades\Site;

class GetAgendaByDateController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, $agenda, $date)
    {
        /** @var \Statamic\Contracts\Entries\EntryQueryBuilder $query */
        $query = \Statamic\Facades\Entry::query();

        //TODO: It still gets in EN all the time - Get the handle of the current site (e.g., 'en' or 'ar')
        $currentLocale = Site::current()->handle();

         $sessions = $query
        ->orderBy('start_time', 'asc')
        ->orderBy('order', 'asc')
        ->where('collection', 'sessions')
        ->whereStatus('published')
        ->where('site', $currentLocale)
        ->whereTaxonomy('agendas::' . $agenda)
        ->whereDate('event_date', Carbon::parse($date)->format('Y-m-d'))
        ->select([
            'title',
            'slug',
            'event_date',
            'start_time',
            'end_time',
            'halls',
            'content',
            'speakers',
            'agendas',
        ])
        ->get()
        ->map(function ($item) {
            return [
                'title' => $item->get('title'),
                'slug' => $item->get('slug'),
                'event_date' => $item->in('en')->get('event_date'),
                'start_time' => $item->in('en')->get('start_time'),
                'end_time' => $item->in('en')->get('end_time') ?? '22:00',
                'show_time_duration' => AgendaHelper::shouldShowTimeDuration($item->in('en')->get('start_time'), $item->in('en')->get('end_time')),
                //TODO: in('en') was added to show translated fields for halls. please remove once changed to localizable
                'hall' => AgendaHelper::getHall($item->in('en')->get('halls', [])),
                'description' => $item->get('content'),
                'speakers' => AgendaHelper::getSpeakers($item->get('speakers', [])),
            ];
        });

    return response()->json($sessions);
    }
}
