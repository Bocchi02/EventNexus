@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4 text-gray-100">Admin Dashboard</h1>
    <div class="grid md:grid-cols-3 gap-6">
        <div class="bg-gray-800 p-4 rounded-lg shadow">
            <h2 class="text-lg font-semibold text-gray-200">Total Users</h2>
            <p class="text-3xl font-bold mt-2 text-blue-400">123</p>
        </div>
        <div class="bg-gray-800 p-4 rounded-lg shadow">
            <h2 class="text-lg font-semibold text-gray-200">Total Events</h2>
            <p class="text-3xl font-bold mt-2 text-green-400">12</p>
        </div>
        <div class="bg-gray-800 p-4 rounded-lg shadow">
            <h2 class="text-lg font-semibold text-gray-200">Pending Approvals</h2>
            <p class="text-3xl font-bold mt-2 text-yellow-400">5</p>
        </div>
    </div>
</div>
@endsection
