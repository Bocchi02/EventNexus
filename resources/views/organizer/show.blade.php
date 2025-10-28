@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <a href="{{ route('organizer.dashboard') }}" 
       class="text-blue-400 hover:text-blue-300">&larr; Back to My Events</a>

    <div class="bg-gray-800 rounded-lg p-6 mt-4">
        <div class="flex flex-col md:flex-row gap-6">
            @if($event->cover_image)
                <img src="{{ asset($event->cover_image) }}" 
                     alt="{{ $event->title }}" 
                     class="w-full md:w-1/3 h-25 object-cover rounded-lg">
            @endif

            <div class="flex-1">
                <h1 class="text-3xl font-bold text-gray-100 mb-2">{{ $event->title }}</h1>
                <p class="text-gray-400 mb-4">{{ $event->venue }}</p>
                <p class="text-gray-300">{{ $event->description }}</p>

                <div class="mt-6">
                    <p class="text-gray-300"><strong class="text-gray-200">Start:</strong> 
                        {{ $event->start_date->format('M d, Y h:i A') }}</p>
                    <p class="text-gray-300"><strong class="text-gray-200">End:</strong> 
                        {{ $event->end_date->format('M d, Y h:i A') }}</p>
                    <p class="mt-2">
                        <strong class="text-gray-200">Status:</strong> 
                        <span class="capitalize {{ $event->status === 'upcoming' ? 'text-blue-400' : ($event->status === 'ongoing' ? 'text-yellow-400' : 'text-green-400') }}">
                            {{ $event->status }}
                        </span>
                    </p>1
                </div>
            </div>
        </div>

        @if($event->gallery_images)
        <div class="mt-8">
            <h2 class="text-xl text-gray-200 mb-3">Gallery</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                @foreach(json_decode($event->gallery_images, true) as $image)
                    <img src="{{ asset($image) }}" class="rounded-lg object-cover h-40 w-full" alt="Gallery Image">
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
