<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\DayAgenda;

class DayAgendaController extends Controller
{
    public function index()
    {
        $agendas = DayAgenda::published()
            ->orderBy('sort')
            ->orderBy('title')
            ->get();

        return view('day-agendas.index', compact('agendas'));
    }

    public function show(DayAgenda $dayAgenda)
    {
        abort_unless($dayAgenda->isPublished() || (auth()->user()?->isAdmin() ?? false), 404);

        $more = DayAgenda::published()
            ->whereKeyNot($dayAgenda->getKey())
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view('day-agendas.show', ['agenda' => $dayAgenda, 'more' => $more]);
    }
}
