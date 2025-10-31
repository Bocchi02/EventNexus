@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6 text-black-100">Client Dashboard</h1>
    <p class="text-xl text-black-300 mb-6">Welcome, {{ Auth::user()->full_name }}! Here are the events assigned to you.</p>

    <div class="bg-gray-800 rounded-lg shadow-xl overflow-hidden">
        <table class="min-w-full text-gray-200">
            <thead class="bg-gray-700">
                <tr>
                    <th class="px-4 py-2 text-left">Cover</th>
                    <th class="px-4 py-2 text-left">Title</th>
                    <th class="px-4 py-2 text-left">Venue</th>
                    <th class="px-4 py-2 text-left">Start Date</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-right">Details</th>
                </tr>
            </thead>
            <tbody>
                @forelse($events as $event)
                <tr class="border-t border-gray-700 hover:bg-gray-700">
                    <td class="px-4 py-2">
                        @if($event->cover_image)
                            <img src="{{ asset($event->cover_image) }}" alt="{{ $event->title }}" 
                                class="w-16 h-16 object-cover rounded">
                        @else
                            <div class="w-16 h-16 bg-gray-600 rounded flex items-center justify-center text-gray-400">
                                No Image
                            </div>
                        @endif
                    </td>
                    <td class="px-4 py-3 font-semibold">{{ $event->title }}</td>
                    <td class="px-4 py-3">{{ $event->venue }}</td>
                    <td class="px-4 py-3">{{ $event->start_date->format('M d, Y h:i A') }}</td>
                    <td class="px-4 py-3">
                        <span class="px-3 py-1 text-s font-semibold rounded-full bg-blue-600">
                            {{ ucfirst($event->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('client.events.show', $event) }}" class="text-blue-400 hover:text-blue-300">View Details</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-gray-400">
                        You currently have no assigned events.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection