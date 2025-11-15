<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    public function dashboard()
    {
        $clientId = Auth::id();

        // --- Original Counts ---
        $totalEvents = Event::where('client_id', $clientId)->count();
        $upcomingEvents = Event::where('client_id', $clientId)
                                ->where('status', 'upcoming')
                                ->count();
        $completedEvents = Event::where('client_id', $clientId)
                                ->where('status', 'completed')
                                ->count();
        $cancelledEvents = Event::where('client_id', $clientId)
                                ->where('status', 'cancelled')
                                ->count();

        // --- Return data to the view ---
        return view('client.dashboard', compact(
            'totalEvents', 
            'upcomingEvents', 
            'completedEvents', 
            'cancelledEvents'
        ));
    }

    public function showEvent(Event $event)
    {
        $event->load('organizer');
        return view('client.show', compact('event'));
    }
}