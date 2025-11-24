<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\GuestInvitationMail;
use App\Models\PendingGuest;
use Illuminate\Support\Facades\DB;

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


    // View Guest
    public function getGuestsList($eventId) 
{
    $event = Event::findOrFail($eventId);
    
    // Get registered/accepted guests from event_guest pivot table
    $registeredGuests = \DB::table('event_guest')
        ->join('users', 'event_guest.user_id', '=', 'users.id')
        ->where('event_guest.event_id', $eventId)
        ->whereIn('event_guest.status', ['accepted', 'pending']) // Include accepted guests
        ->select(
            'users.firstname', 
            'users.middlename', 
            'users.lastname', 
            'users.email',
            'event_guest.status'
        )
        ->get()
        ->map(function($guest) {
            return [
                'full_name' => trim($guest->firstname . ' ' . ($guest->middlename ?? '') . ' ' . $guest->lastname),
                'email' => $guest->email,
                'status' => $guest->status
            ];
        });
    
    // Get pending invitations from pending_guests table (not yet registered users)
    $pendingGuests = PendingGuest::where('event_id', $eventId)
        ->get()
        ->map(function($invite) {
            return [
                'full_name' => trim($invite->firstname . ' ' . ($invite->middlename ?? '') . ' ' . $invite->lastname),
                'email' => $invite->email
            ];
        });
    
    return response()->json([
        'eventTitle' => $event->title,
        'registered' => $registeredGuests->where('status', 'accepted'), // Only show accepted
        'pending' => $pendingGuests
    ]);
}
}