@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4 text-gray-100 flex justify-between">
        My Events
        <button onclick="document.getElementById('addEventForm').classList.toggle('hidden')"
            class="bg-blue-600 hover:bg-blue-500 text-white px-3 py-1 rounded">
            + Create Event
        </button>
    </h1>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="bg-green-700 text-white p-3 rounded mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-700 text-white p-3 rounded mb-4">{{ session('error') }}</div>
    @endif

    {{-- Validation errors --}}
    @if ($errors->any())
        <div class="bg-red-700 text-white p-3 rounded mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Add event form --}}
    <div id="addEventForm" class="hidden bg-gray-800 p-4 rounded-lg mb-6">
        <form method="POST" action="{{ route('organizer.storeEvent') }}" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-gray-300">Event Title</label>
                    <input type="text" name="title" value="{{ old('title') }}" required 
                        class="w-full bg-gray-700 text-gray-100 rounded px-3 py-2">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-gray-300">Description</label>
                    <textarea name="description" rows="4" required 
                        class="w-full bg-gray-700 text-gray-100 rounded px-3 py-2">{{ old('description') }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-gray-300">Venue</label>
                    <input type="text" name="venue" value="{{ old('venue') }}" required 
                        class="w-full bg-gray-700 text-gray-100 rounded px-3 py-2">
                </div>

                <div>
                    <label class="block text-gray-300">Start Date & Time</label>
                    <input type="datetime-local" name="start_date" value="{{ old('start_date') }}" required 
                        class="w-full bg-gray-700 text-gray-100 rounded px-3 py-2">
                </div>

                <div>
                    <label class="block text-gray-300">End Date & Time</label>
                    <input type="datetime-local" name="end_date" value="{{ old('end_date') }}" required 
                        class="w-full bg-gray-700 text-gray-100 rounded px-3 py-2">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-gray-300">Cover Image (Optional)</label>
                    <input type="file" name="cover_image" accept="image/*" 
                        class="w-full bg-gray-700 text-gray-100 rounded px-3 py-2">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-gray-300">Gallery Images (Optional)</label>
                    <input type="file" name="gallery_images[]" accept="image/*" multiple 
                        class="w-full bg-gray-700 text-gray-100 rounded px-3 py-2">
                    <p class="text-gray-400 text-sm mt-1">You can select multiple images</p>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="bg-green-600 hover:bg-green-500 text-white px-4 py-2 rounded">
                    Create Event
                </button>
            </div>
        </form>
    </div>

    {{-- Events table --}}
    <div class="bg-gray-800 rounded-lg overflow-hidden">
        <table class="min-w-full text-gray-200">
            <thead class="bg-gray-700">
                <tr>
                    <th class="px-4 py-2 text-left">Cover</th>
                    <th class="px-4 py-2 text-left">Title</th>
                    <th class="px-4 py-2 text-left">Venue</th>
                    <th class="px-4 py-2 text-left">Start Date</th>
                    <th class="px-4 py-2 text-left">End Date</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($events as $event)
                <tr class="border-t border-gray-700">
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
                    <td class="px-4 py-2">{{ $event->title }}</td>
                    <td class="px-4 py-2">{{ $event->venue }}</td>
                    <td class="px-4 py-2">{{ $event->start_date->format('M d, Y h:i A') }}</td>
                    <td class="px-4 py-2">{{ $event->end_date->format('M d, Y h:i A') }}</td>
                    <td class="px-4 py-2">
                        <form method="POST" action="{{ route('organizer.toggleStatus', $event->id) }}">
                            @csrf
                            <button class="px-3 py-1 rounded 
                                {{ $event->status === 'upcoming' ? 'bg-blue-600 hover:bg-blue-500' : '' }}
                                {{ $event->status === 'ongoing' ? 'bg-yellow-600 hover:bg-yellow-500' : '' }}
                                {{ $event->status === 'completed' ? 'bg-green-600 hover:bg-green-500' : '' }}
                                {{ $event->status === 'cancelled' ? 'bg-red-600 hover:bg-red-500' : '' }}">
                                {{ ucfirst($event->status) }}
                            </button>
                        </form>
                    </td>
                    <td class="px-4 py-2 text-right">
                        <div class="flex gap-2 justify-end">
                            <a href="#" class="text-blue-400 hover:text-blue-300">View</a>
                            <form method="POST" action="{{ route('organizer.deleteEvent', $event->id) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Are you sure?')" 
                                    class="text-red-400 hover:text-red-300">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-400">
                        No events yet. Create your first event!
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection