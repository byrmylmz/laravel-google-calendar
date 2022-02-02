<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Calendar;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = auth()->user()->events()
            ->orderBy('started_at', 'desc')
            ->get();

        return view('events', compact('events'));
    }
}