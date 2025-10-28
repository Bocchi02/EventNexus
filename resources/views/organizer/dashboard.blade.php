@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <h1 class="mb-3">Organizer Dashboard</h1>
            <p>Welcome, {{ Auth::user()->name }}!</p>
            <p>GOD PLEASE HELP THIS DAMNED CHILD</p>
        </div>
    </div>
</div>

<div class="grid md:grid-cols-3 gap-6">
    <div class="bg-gray-800 p-4 rounded-lg shadow">
        <h2 class="text-lg font-semibold text-gray-200">Total Events</h2>
        <p class="text-3xl font-bold text-white">{{ $totalEvents }}</p>
    </div>

    <div class="bg-gray-800 p-4 rounded-lg shadow">
        <h2 class="text-lg font-semibold text-gray-200">Upcoming</h3>
        <p class="text-3xl font-bold text-blue-400">{{ $upcomingEvents }}</p>
    </div>

    <div class="bg-gray-800 p-4 rounded-lg shadow">
        <h2 class="text-lg font-semibold text-gray-200">Completed</h3>
        <p class="text-3xl font-bold text-green-400">{{ $completedEvents }}</p>
    </div>
</div>

<div class="container mx-auto p-6">
    <a href="{{ route('organizer.events') }}"
        class="text-blue-400">Access the Events HUEHUEHUE</a>
</div>
@endsection
