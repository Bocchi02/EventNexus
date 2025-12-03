<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;   

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    /**
     * Display dashboard summary.
     */
    public function dashboard()
    {
        $totalUsers = User::count();

         // Users created this week
        $thisWeek = User::whereBetween('created_at', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ])->count();

        // Users created last week
        $lastWeek = User::whereBetween('created_at', [
            Carbon::now()->subWeek()->startOfWeek(),
            Carbon::now()->subWeek()->endOfWeek()
        ])->count();

        // Compute growth rate (avoid division by zero)
        $growth = $lastWeek > 0 ? (($thisWeek - $lastWeek) / $lastWeek) * 100 : ($thisWeek > 0 ? 100 : 0);

        $activeUsers = User::where('status', 'active')->count();
        $inactiveUsers = User::where('status', 'inactive')->count();

        $upcomingEvents = Event::where('status', 'upcoming')
            ->orderBy('start_date', 'asc')
            ->take(5)
            ->get();

        $cancelledEvents = Event::where('status', 'cancelled')
            ->orderBy('start_date', 'asc')
            ->take(5)
            ->get();


        return view('admin.dashboard', compact('totalUsers', 'thisWeek', 'lastWeek', 'growth', 'upcomingEvents', 'cancelledEvents'));
    }

    /**
     * Show all users.
     */
    public function users()
    {   
        $roles = Role::all();
        return view('admin.users', compact('roles'));
    }

    public function getUsers()
    {
        $users = User::with('roles')->get()->map(function ($user) {
            return [
                'id' => $user->id,
                'firstname' => $user->firstname,
                'middlename' => $user->middlename,
                'lastname' => $user->lastname,
                'email' => $user->email,
                'role' => $user->roles->pluck('name')->implode(', '), // combine role names
                'status' => $user->status,
                'created_at' => $user->created_at->toDateString(),
            ];
        });

        return response()->json(['data' => $users]);
    }

    /**
     * Store a new user.
     */
    public function storeUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|string'
        ]);

        //  Return JSON validation errors for AJAX
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        $user = User::create([
            'firstname' => $validated['firstname'],
            'middlename' => $validated['middlename'] ?? null,
            'lastname' => $validated['lastname'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'status' => 'active'
        ]);

        $user->assignRole($validated['role']);
        $user->load('roles');

        return response()->json([
            'success' => 'User created successfully',
            'title' => 'Saved Successfully!',
        ], 200);
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

        return response()->json([
            'success' => true,
            'message' => 'User status updated.',
            'status' => $user->status,
        ]);
    }


    public function editUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed', // Password is optional on edit
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $validated = $validator->validated();

        $user->firstname = $validated['firstname'];
        $user->middlename = $validated['middlename'] ?? null;
        $user->lastname = $validated['lastname'];
        $user->email = $validated['email'];

        if (!empty($validated['password'])) {
            $user->password = bcrypt($validated['password']);
        }

        $user->save();

         // Return the new user as JSON
        return response()->json([
            'success' => 'User updated successfully',
            'title' => 'Saved Successfully!',
        ]);
        //return redirect()->back()->with('success', 'User updated successfully.');
    }

    /**
     * Delete a user.
     */
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        // Prevent deleting self (optional)
        if (Auth::id() === $user->id) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return response()->json('success', 'User deleted successfully.');
    }



}
