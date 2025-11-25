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

    public function inviteGuest(Request $request, $eventId) 
    {
        $request->validate([
            'email' => 'required|email',
            'firstname' => 'required|string',
            'lastname' => 'required|string',
        ]);
        
        $email = $request->input('email');
        $event = Event::findOrFail($eventId);

        // Check if user already exists in the system
        $existingUser = User::where('email', $email)->first();

        if ($existingUser) {
            // 1. Attach them to the event with 'pending' status if not already attached
            // We use syncWithoutDetaching to avoid overwriting if they are already there
            if (!$event->guests->contains($existingUser->id)) {
                $event->guests()->attach($existingUser->id, ['status' => 'pending']);
            } else {
                // If they are already attached, maybe reset status to pending?
                // Optional: $event->guests()->updateExistingPivot($existingUser->id, ['status' => 'pending']);
            }

            // 2. Send the RSVP Email (Signed URL)
            // We need to create a Mailable for this (e.g., ExistingUserRSVP)
            // For now, let's assume you will create it next.
             Mail::to($existingUser->email)->send(new \App\Mail\EventRSVP($event, $existingUser));

            return response()->json(['message' => 'Invitation sent to existing user.'], 200);

        } else {
            // --- User is NOT registered (Use your teammate's PendingGuest logic) ---
            
            // Check for duplicate pending invites
            $pendingInvite = PendingGuest::where('email', $email)->where('event_id', $eventId)->first();
            if ($pendingInvite) {
                 return response()->json(['message' => 'Invitation already pending.'], 409);
            }

            // Create Pending Guest
            $token = Str::random(32);
            $pending = PendingGuest::create([
                'event_id' => $event->id,
                'client_id' => Auth::id(),
                'email' => $email,
                'firstname' => $request->input('firstname'),
                'lastname' => $request->input('lastname'),
                'invite_token' => $token,
            ]);

            // Send New Guest Email
            Mail::to($email)->send(new GuestInvitationMail($pending));
            
            return response()->json(['message' => 'Invitation sent to new guest.'], 200);
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

// EXISTING USER RSVP RESPONSE
    // This route will be signed (protected) so nobody can fake it
    public function respond(Request $request, Event $event, User $user, $status)
    {
        // 1. Verify Signature is handled by the route middleware usually, 
        // but strict checking is good practice if you don't use middleware.
        if (! $request->hasValidSignature()) {
            abort(403, 'This link has expired or is invalid.');
        }

        // 2. Validate Status
        if (!in_array($status, ['accepted', 'declined'])) {
            abort(400, 'Invalid status.');
        }

        // 3. Update the Pivot Table
        $event->guests()->updateExistingPivot($user->id, [
            'status' => $status
        ]);

        // 4. Show a Thank You Page
        // You can create a simple view resources/views/rsvp/thankyou.blade.php
        return view('rsvp.thankyou', [
            'event' => $event,
            'status' => $status
        ]);
    }
}