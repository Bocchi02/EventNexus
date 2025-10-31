<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    /**
     * Display dashboard summary.
     */
    public function dashboard()
    {
        $totalUsers = User::count();
        $activeUsers = User::where('status', 'active')->count();
        $inactiveUsers = User::where('status', 'inactive')->count();

        return view('admin.dashboard', compact('totalUsers', 'activeUsers', 'inactiveUsers'));
    }

    /**
     * Show all users.
     */
    public function users()
    {
        $users = User::with('roles')->get();
        $roles = Role::all();

        return view('admin.users', compact('users', 'roles'));
    }

    /**
     * Store a new user.
     */
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|string'
        ]);

        $user = User::create([
            'first_name' => $validated['firstname'],      // Changed
            'middle_name' => $validated['middlename'],    // Changed
            'last_name' => $validated['lastname'],        // Changed
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'status' => 'active'
        ]);

        $user->assignRole($validated['role']);

        return redirect()->back()->with('success', 'User added successfully.');
    }


    /**
     * Assign a role to a user.
     */
    public function assignRole(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->syncRoles([$request->role]); // replace existing role with new one

        return redirect()->back()->with('success', 'Role assigned successfully.');
    }

    /**
     * Update user status (activate/deactivate)
     */
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->status = $user->status === 'active' ? 'inactive' : 'active';
        $user->save();

        return redirect()->back()->with('success', 'User status updated.');
    }

    /**
     * Delete a user.
     */
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        // Prevent deleting self (optional)
        if (auth()->id() === $user->id) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->back()->with('success', 'User deleted successfully.');
    }
}
