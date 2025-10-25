<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardRedirectController extends Controller
{
    public function redirect()
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        // Get the first assigned role (assuming one role per user)
        $role = $user->roles->pluck('name')->first();

        return match ($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'organizer' => redirect()->route('organizer.dashboard'),
            'client' => redirect()->route('client.dashboard'),
            'guest' => redirect()->route('guest.dashboard'),
            'staff' => redirect()->route('staff.dashboard'),
            'supplier' => redirect()->route('supplier.dashboard'),
            default => redirect()->route('login'), // fallback
        };
    }
}
