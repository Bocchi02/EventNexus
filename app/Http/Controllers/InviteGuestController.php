<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use App\Models\PendingGuest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\GuestInvitationMail;
use Illuminate\Support\Facades\Auth;

class InviteGuestController extends Controller
{
    // SEND INVITE
    public function sendInvite(Request $request, Event $event)
    {
        $request->validate([
            'email' => 'required|email',
            'name'  => 'nullable|string|max:255',
        ]);

        // Check if user already exists
        $existingUser = User::where('email', $request->email)->first();

        // If user exists -> directly attach to event
        if ($existingUser) {
            $event->guests()->syncWithoutDetaching([$existingUser->id]);

            return response()->json([
                'message' => 'Guest already has an account and was added to the event.'
            ]);
        }

        // Generate invite token
        $token = Str::random(32);

        // Create pending guest
        $pending = PendingGuest::create([
            'event_id'   => $event->id,
            'client_id'  => Auth::id(),
            'email'      => $request->email,
            'name'       => $request->name,
            'invite_token' => $token,
        ]);

        // Send invitation email
        Mail::to($request->email)->send(new GuestInvitationMail($pending));

        return response()->json([
            'message' => 'Invitation sent successfully!'
        ]);
    }

    public function inviteGuest(Request $request)
{
    // ... validation and data gathering ...
    
    $email = $request->input('email');
    $eventId = $request->input('event_id');

    // 🚀 STEP 1: Check if the user already exists in the main users table
    $existingUser = User::where('email', $email)->first();

    if ($existingUser) {
        // --- User is registered (e.g., Bocchi) ---
        
        // 1a. Find the Event model (assuming you passed $event, or fetch it)
        $event = Event::findOrFail($eventId); 
        
        // 1b. Link the existing user to the event in the event_guests table (many-to-many)
        // This implicitly prevents the 'Duplicate Entry' error by skipping 'pending_guests'
        $event->guests()->syncWithoutDetaching([$existingUser->id]);

        // Return a success message that the registered user was added
        return response()->json(['message' => 'Registered user added to event.'], 200);

    } else {
        // --- User is not registered (Proceed with PENDING invite) ---
        
        // 2a. Check if a pending invite for this email/event already exists
        $pendingInvite = PendingGuest::where('email', $email)
                                     ->where('event_id', $eventId)
                                     ->first();
                                     
        if ($pendingInvite) {
            // User already has a pending invite for this specific event
             return response()->json(['message' => 'User already has a pending invitation for this event.'], 409);
        }

        // 2b. If no user and no pending invite, proceed with the INSERT into pending_guests
        // Your existing insert logic goes here...
        
        // return response()->json(['message' => 'Invitation sent successfully.'], 200);
    }
}


    // GUEST ACCEPTS INVITE
    public function acceptInvite($token)
    {
        $pending = PendingGuest::where('invite_token', $token)->first();

        if (!$pending) {
            abort(404, "Invalid invitation link.");
        }

        // Check if user already exists
        $user = User::where('email', $pending->email)->first();

        // If not exist, create one
        if (!$user) {
            $user = User::create([
                'firstname' => $pending->name ?? 'Guest',
                'lastname'  => '',
                'email'     => $pending->email,
                'password'  => bcrypt(Str::random(12)),
            ]);

            // Assign guest role
            $user->assignRole('guest');
        }

        // Attach to event
        $pending->event->guests()->syncWithoutDetaching([$user->id]);

        // Update status
        $pending->update(['status' => 'accepted']);

        return redirect('/')
            ->with('success', 'Invitation accepted! You may now view the event.');
    }
}
