<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Event;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $businesses = $user->businesses()->with('categories')->get();
        $events = Event::where('submitted_by', $user->id)
            ->orderBy('event_date', 'desc')
            ->get();

        return view('business.dashboard', compact('businesses', 'events'));
    }
}
