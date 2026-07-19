<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;

class WalkingTourController extends Controller
{
    public function index()
    {
        $tour = require resource_path('data/walking-tour.php');

        return view('pages.historic-walking-tour', ['tour' => $tour]);
    }
}
