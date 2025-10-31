<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function dashboard()
    {
        $clientId = auth()->id();

        $events = Event::where('client_id', $clientId)
                       ->with('organizer') 
                       ->orderBy('start_date', 'asc')
                       ->get();

        return view('client.dashboard', compact('events'));
    }

    public function showEvent(Event $event)
    {
        $event->load('organizer');
        return view('client.show', compact('event'));
    }
}