<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller; // ⬅️ ADD THIS LINE (or verify it exists)

class GuestController extends Controller // ⬅️ The parent class is now correctly referenced
{
    public function dashboard()
    {
        $guestId = auth()->id(); 

        $invitedEventsQuery = Event::whereHas('guests', function ($query) use ($guestId) {
            $query->where('user_id', $guestId); 
        });
        
        // --- Calculate Counts ---
        $totalEvents = $invitedEventsQuery->count();
        $upcomingEvents = $invitedEventsQuery->clone()->where('status', 'upcoming')->count();
        $completedEvents = $invitedEventsQuery->clone()->where('status', 'completed')->count();
        $cancelledEvents = $invitedEventsQuery->clone()->where('status', 'cancelled')->count();

        return view('guest.dashboard', compact('totalEvents', 'upcomingEvents', 'completedEvents', 'cancelledEvents'));
    }

    public function events() // ⬅️ THIS NAME MUST MATCH THE ROUTE'S CALL
    {
        $guestId = auth()->id();
        
        // Fetch all events where the current user is listed as a guest
        // Assumes 'guests' relationship is defined in Event model
        $events = Event::whereHas('guests', function ($query) use ($guestId) {
            $query->where('user_id', $guestId); 
        })->get();

        // Return the DataTable view for the guest
        return view('guest.events', compact('events')); 
    }

    public function getEventsAjax()
    {
        if (!auth()->check()) {
            return response()->json(['data' => []], 401);
        }
        
        try {
            $guestId = auth()->id();
            
            $events = Event::whereHas('guests', function ($query) use ($guestId) {
                    $query->where('user_id', $guestId); 
                })
                ->with(['organizer', 'guests' => function($query) use ($guestId) {
                    $query->where('user_id', $guestId);
                }])
                ->get()
                ->map(function ($event) {
                    // Get the invitation status from the pivot table
                    $invitationStatus = $event->guests->first()->pivot->status ?? 'pending';
                    
                    return [
                        'id' => $event->id,
                        'title' => $event->title,
                        'venue' => $event->venue,
                        'start_date' => $event->start_date ? $event->start_date->format('Y-m-d H:i:s') : null,
                        'end_date' => $event->end_date ? $event->end_date->format('Y-m-d H:i:s') : null,
                        'status' => $event->status,
                        'invitation_status' => $invitationStatus, // Add this
                    ];
                });

            return response()->json(['data' => $events], 200); 

        } catch (\Exception $e) {
            \Log::error("Guest AJAX DataTables Error: " . $e->getMessage());
            return response()->json(['data' => [], 'error' => 'Server failed to retrieve data.'], 500); 
        }
    }

    // Show event
    public function show($id)
    {
        $guestId = auth()->id();
        
        // Make sure the guest is actually invited to this event
        $event = Event::whereHas('guests', function ($query) use ($guestId) {
                $query->where('user_id', $guestId);
            })
            ->with('organizer:id,firstname,lastname,middlename')
            ->findOrFail($id);
        
        // Add full_name attribute for the organizer
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
            'status' => 'required|in:accepted,declined,cancelled'
        ]);
        
        $guestId = auth()->id();
        
        // Update the status in the event_guest pivot table
        $updated = \DB::table('event_guest')
            ->where('event_id', $eventId)
            ->where('user_id', $guestId)
            ->update(['status' => $request->status]);
        
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
