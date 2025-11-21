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
        // If the user isn't logged in, fail immediately (though middleware should handle this)
        if (!auth()->check()) {
            return response()->json(['data' => []], 401);
        }
        
        try {
            $guestId = auth()->id();
            
            $events = Event::whereHas('guests', function ($query) use ($guestId) {
                $query->where('user_id', $guestId); 
            })
            // Eager load organizer data for the view modal
            ->with('organizer') 
            ->get();

            // DataTables expects the data wrapped in a 'data' key.
            return response()->json(['data' => $events], 200); 

        } catch (\Exception $e) {
            // Log the error for review in storage/logs/laravel.log
            \Log::error("Guest AJAX DataTables Error: " . $e->getMessage() . " on file " . $e->getFile() . " line " . $e->getLine());
            
            // Return an empty array on failure to prevent the DataTables alert
            return response()->json(['data' => [], 'error' => 'Server failed to retrieve data.'], 500); 
        }
    }
}
