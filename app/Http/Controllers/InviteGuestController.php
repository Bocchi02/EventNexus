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
    public function inviteGuest(Request $request, $eventId) 
    {
        $request->validate([
            'email' => 'required|email',
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'seats' => 'required|integer|min:1',
        ]);
        
        $email = $request->input('email');
        $seats = $request->input('seats');
        $event = Event::findOrFail($eventId);

        // Check if user already exists
        $user = User::where('email', $email)->first();

        // --- SCENARIO 1: USER DOES NOT EXIST (AUTO-REGISTER) ---
        if (!$user) {
            // 1. Set the default password
            $rawPassword = Str::random(8);

            // 2. Create the User immediately
            $user = User::create([
                'firstname' => $request->input('firstname'),
                'lastname'  => $request->input('lastname'),
                'email'     => $email,
                'password'  => bcrypt($rawPassword),
            ]);

            // 3. Assign Spatie Role
            $user->assignRole('guest');

            // 4. Attach to Event
            $event->guests()->attach($user->id, [
                'status' => 'pending', 
                'seats' => $seats
            ]);

            // 5. Send Email with Credentials
            Mail::to($user->email)->send(new GuestInvitationMail($user, $event, $rawPassword));

            return response()->json(['message' => 'Account created and invitation sent!'], 200);
        }

        // --- SCENARIO 2: USER ALREADY EXISTS ---
        else {
            if (!$event->guests->contains($user->id)) {
                $event->guests()->attach($user->id, [
                    'status' => 'pending', 
                    'seats' => $seats
                ]);
            } else {
                $event->guests()->updateExistingPivot($user->id, ['seats' => $seats]);
            }

            // Send standard RSVP email (No password needed)
            // Mail::to($user->email)->send(new \App\Mail\EventRSVP($event, $user));

            return response()->json(['message' => 'Invitation sent to existing user.'], 200);
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

        $pending->event->guests()->syncWithoutDetaching([$user->id=> ['seats' => $pending->seats]]);

        $pending->update(['status' => 'accepted']);

        return redirect('/')
            ->with('success', 'Invitation accepted! You may now view the event.');
    }
}