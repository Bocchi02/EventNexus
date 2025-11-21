<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\GuestInvitationMail;
use App\Models\PendingGuest;

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
        $event->load('client');
        return view('client.events', compact('event'));
    }

    public function getEvents()
    {
        $events = Event::where('client_id', Auth::id())
            ->with('client')
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'client' => $event->client 
                        ? $event->client->firstname . ' ' . $event->client->lastname 
                        : 'N/A',
                    'venue' => $event->venue,
                    //  Keep both date + time (Philippine standard)
                    'start_date' => $event->start_date ? $event->start_date->format('Y-m-d H:i:s') : null,
                    'end_date' => $event->end_date ? $event->end_date->format('Y-m-d H:i:s') : null,
                    'status' => $event->status,
                    'created_at' => $event->created_at ? $event->created_at->format('Y-m-d H:i:s') : null,
                ];
            });

        return response()->json(['data' => $events]);
    }

    public function show($id)
    {
        $event = Event::with('client:id,firstname,lastname,middlename')->findOrFail($id);

        // Add a fallback if client doesn't exist
        if (!$event->client) {
            $event->client = (object)['full_name' => 'Unknown Client'];
        }

        return response()->json($event);
    }

}