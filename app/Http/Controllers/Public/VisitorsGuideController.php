<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\VisitorsGuide;
use App\Services\VisitorsGuidePdf;

class VisitorsGuideController extends Controller
{
    public function show(VisitorsGuide $guide)
    {
        return view('pages.visitors-guide', [
            'directory' => $guide->directory(),
            'calendar' => $guide->signatureEventsByMonth(),
            'upcoming' => $guide->upcomingEvents(6),
            'timeline' => $guide->timeline(),
            'clues' => $guide->scavengerClues(),
            'driveTimes' => VisitorsGuide::DRIVE_TIMES,
            'updated' => $guide->lastUpdated(),
        ]);
    }

    /** Always the current guide: the PDF is rebuilt whenever content changes. */
    public function download(VisitorsGuidePdf $pdf)
    {
        return response()->download(
            $pdf->path(),
            'Downtown-Bellefontaine-Visitors-Guide-'.now()->format('Y-m').'.pdf',
            ['Content-Type' => 'application/pdf', 'Cache-Control' => 'no-cache, private'],
        );
    }
}
