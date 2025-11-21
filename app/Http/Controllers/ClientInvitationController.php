<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\GuestInvitationMail;
use App\Models\PendingGuest;

class ClientInvitationController extends Controller
{
    public function index($eventId)
    {
        $event = Event::where('client_id', Auth::id())->findOrFail($eventId);

        $guests = User::role('guest')->get(); // all guest users

        return view('client.guests', compact('event', 'guests'));
    }

    public function list($eventId)
    {
        $event = Event::where('client_id', Auth::id())->findOrFail($eventId);

        return response()->json([
            'data' => $event->invitedGuests()->get()->map(function ($guest) {
                return [
                    'id' => $guest->id,
                    'name' => $guest->full_name,
                    'email' => $guest->email,
                    'role' => $guest->pivot->role,
                    'status' => $guest->pivot->status
                ];
            }),
        ]);
    }

    public function updateStatus(Request $request, $eventId, $guestId)
    {
        $event = Event::where('client_id', Auth::id())->findOrFail($eventId);

        $event->invitedGuests()
            ->updateExistingPivot($guestId, [
                'status' => $request->status
            ]);

        return response()->json([
            'message' => 'Status updated.'
        ]);
    }

    public function remove($eventId, $guestId)
    {
        $event = Event::where('client_id', Auth::id())->findOrFail($eventId);

        $event->invitedGuests()->detach($guestId);

        return response()->json([
            'message' => 'Guest removed from event.'
        ]);
    }

    
}

