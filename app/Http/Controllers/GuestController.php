<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GuestController extends Controller
{
    public function dashboard()
        {
            $guestId = Auth::id();

            // 1. Fetch "My Tickets" (Accepted & Future)
            $myTickets = Event::whereHas('guests', function ($query) use ($guestId) {
                    $query->where('user_id', $guestId)
                        ->where('event_guest.status', 'accepted'); 
                })
                ->where('end_date', '>=', now()) 
                ->with('client') 
                ->orderBy('start_date', 'asc')
                ->get();

            // 2. NEW: Fetch "Pending Invitations"
            $pendingInvites = Event::whereHas('guests', function ($query) use ($guestId) {
                    $query->where('user_id', $guestId)
                        ->where('event_guest.status', 'pending'); // <--- Pending status
                })
                ->where('end_date', '>=', now()) // Only show future events
                ->with('client')
                ->orderBy('created_at', 'desc')
                ->get();

            // 3. Calculate Counts
            $upcomingEvents = $myTickets->count(); 
            $totalEvents    = $myTickets->count() + $pendingInvites->count(); // Accepted + Pending
            $completedEvents = 0; // Simplification for now
            $cancelledEvents = 0; 

            return view('guest.dashboard', compact(
                'myTickets', 
                'pendingInvites', // <--- Pass this to view
                'upcomingEvents', 
                'totalEvents', 
                'completedEvents', 
                'cancelledEvents'
            ));
        }

    public function events()
    {
        $guestId = Auth::id();
        
        $events = Event::whereHas('guests', function ($query) use ($guestId) {
            $query->where('user_id', $guestId); 
        })->get();

        return view('guest.events', compact('events')); 
    }

    public function getEventsAjax()
    {
        if (!Auth::check()) {
            return response()->json(['data' => []], 401);
        }
        
        try {
            $guestId = Auth::id();
            
            $events = Event::whereHas('guests', function ($query) use ($guestId) {
                    $query->where('user_id', $guestId); 
                })
                ->with(['organizer', 'guests' => function($query) use ($guestId) {
                    $query->where('user_id', $guestId);
                }])
                ->get()
                ->map(function ($event) {
                    $invitationStatus = $event->guests->first()->pivot->status ?? 'pending';
                    
                    return [
                        'id' => $event->id,
                        'title' => $event->title,
                        'venue' => $event->venue,
                        'start_date' => $event->start_date ? $event->start_date->format('Y-m-d H:i:s') : null,
                        'end_date' => $event->end_date ? $event->end_date->format('Y-m-d H:i:s') : null,
                        'status' => $event->status,
                        'invitation_status' => $invitationStatus,
                    ];
                });

            return response()->json(['data' => $events], 200); 

        } catch (\Exception $e) {
            Log::error("Guest AJAX DataTables Error: " . $e->getMessage());
            return response()->json(['data' => [], 'error' => 'Server failed to retrieve data.'], 500); 
        }
    }

    public function show($id)
    {
        $guestId = Auth::id();
        
        $event = Event::whereHas('guests', function ($query) use ($guestId) {
                $query->where('user_id', $guestId);
            })
            ->with([
                'organizer:id,firstname,lastname,middlename',
                'guests' => function($query) use ($guestId) {
                    $query->where('user_id', $guestId);
                }
            ])
            ->findOrFail($id);
        
        // Get the guest data with pivot information
        $guestData = $event->guests->first();
        $event->my_allocated_seats = $guestData && $guestData->pivot ? $guestData->pivot->seats : 1;

        if ($event->organizer) {
            $event->organizer->full_name = trim(
                $event->organizer->firstname . ' ' . 
                ($event->organizer->middlename ?? '') . ' ' . 
                $event->organizer->lastname
            );
        }
        
        return response()->json($event);
    }

    public function respondToInvitation(Request $request, $eventId)
    {
        $request->validate([
            'status' => 'required|in:accepted,declined,cancelled',
            'seats_confirmed' => 'nullable|integer|min:1'
        ]);
        
        $guestId = Auth::id();

        // Build the update data array
        $updateData = ['status' => $request->status];

        // If accepting and seats are provided, update seats
        if ($request->status === 'accepted' && $request->has('seats_confirmed')) {
            $updateData['seats'] = $request->seats_confirmed;
        }
        
        // Use the $updateData array in the update call
        $updated = DB::table('event_guest')
            ->where('event_id', $eventId)
            ->where('user_id', $guestId)
            ->update($updateData); // Use $updateData instead of just status
        
        if ($updated) {
            $statusMessages = [
                'accepted' => 'You have accepted the invitation!',
                'declined' => 'You have declined the invitation.',
                'cancelled' => 'You have cancelled your attendance.'
            ];
            
            $message = $statusMessages[$request->status] ?? 'Invitation status updated.';
            
            return response()->json(['message' => $message], 200);
        }
        
        return response()->json(['message' => 'Failed to update invitation status.'], 500);
    }
}