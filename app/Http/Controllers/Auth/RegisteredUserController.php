<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'firstname' => ['required', 'string', 'max:255'],
            'middlename' => ['nullable', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],

            'role' => ['required', 'in:organizer,client,guest'],

            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],

            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Create full name
        $fullName = trim(
            $request->firstname . ' ' .
            ($request->middlename ? $request->middlename . ' ' : '') .
            $request->lastname
        );

        // Create the user
        $user = User::create([
            'name' => $fullName,
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Assign Spatie role
        $user->assignRole($request->role);

        event(new Registered($user));
        Auth::login($user);

        // Role-based redirect
        if ($user->hasRole('organizer')) {
            return redirect()->route('organizer.dashboard');
        }

        if ($user->hasRole('client')) {
            return redirect()->route('client.dashboard');
        }

        if ($user->hasRole('guest')) {
            return redirect()->route('guest.dashboard');
        }

        // fallback
        return redirect()->route('dashboard');
    }

}
