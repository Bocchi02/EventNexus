@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4 text-gray-100 flex justify-between">
        Manage Users
        <button onclick="document.getElementById('addUserForm').classList.toggle('hidden')"
            class="bg-blue-600 hover:bg-blue-500 text-white px-3 py-1 rounded">
            + Add User
        </button>
    </h1>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="bg-green-700 text-white p-3 rounded mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-700 text-white p-3 rounded mb-4">{{ session('error') }}</div>
    @endif

    {{-- Add user form --}}
    <div id="addUserForm" class="hidden bg-gray-800 p-4 rounded-lg mb-6">
        <form method="POST" action="{{ route('admin.storeUser') }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-300">Firstname</label>
                    <input type="text" name="firstname" required class="w-full bg-gray-700 text-gray-100 rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-gray-300">Middle Name</label>
                    <input type="text" name="middlename" class="w-full bg-gray-700 text-gray-100 rounded px-3 py-2">
                </div>

                <div>
                    <label class="block text-gray-300">Last Name</label>
                    <input type="text" name="lastname" required class="w-full bg-gray-700 text-gray-100 rounded px-3 py-2">
                </div>


                <div>
                    <label class="block text-gray-300">Email</label>
                    <input type="email" name="email" required class="w-full bg-gray-700 text-gray-100 rounded px-3 py-2">
                </div>

                <div>
                    <label class="block text-gray-300">Password</label>
                    <input type="password" name="password" required class="w-full bg-gray-700 text-gray-100 rounded px-3 py-2">
                </div>

                <div>
                    <label class="block text-gray-300">Confirm Password</label>
                    <input type="password" name="password_confirmation" required class="w-full bg-gray-700 text-gray-100 rounded px-3 py-2">
                </div>

                <div>
                    <label class="block text-gray-300">Role</label>
                    <select name="role" required class="w-full bg-gray-700 text-gray-100 rounded px-3 py-2">
                        <option value="" disabled selected>-- Select Role --</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="bg-green-600 hover:bg-green-500 text-white px-4 py-2 rounded">
                    Create User
                </button>
            </div>
        </form>
    </div>

    {{-- Users table --}}
    <table class="min-w-full bg-gray-800 text-gray-200 rounded-lg overflow-hidden">
        <thead class="bg-gray-700">
            <tr>
                <th class="px-4 py-2 text-left">Name</th>
                <th class="px-4 py-2 text-left">Email</th>
                <th class="px-4 py-2 text-left">Role</th>
                <th class="px-4 py-2 text-left">Status</th>
                <th class="px-4 py-2 text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr class="border-t border-gray-700">
                <td class="px-4 py-2">{{ $user->name }}</td>
                <td class="px-4 py-2">{{ $user->email }}</td>
                <td class="px-4 py-2">
                    <form method="POST" action="{{ route('admin.assignRole', $user->id) }}">
                        @csrf
                        <select name="role" onchange="this.form.submit()" class="bg-gray-700 text-gray-100 rounded px-2 py-1">
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ $user->roles->contains('name', $role->name) ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </td>
                <td class="px-4 py-2">
                    <form method="POST" action="{{ route('admin.toggleStatus', $user->id) }}">
                        @csrf
                        <button class="px-3 py-1 rounded {{ $user->status === 'active' ? 'bg-green-600 hover:bg-green-500' : 'bg-gray-600 hover:bg-gray-500' }}">
                            {{ ucfirst($user->status ?? 'inactive') }}
                        </button>
                    </form>
                </td>
                <td class="px-4 py-2 text-right">
                    <form method="POST" action="{{ route('admin.deleteUser', $user->id) }}">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-400 hover:text-red-300">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
