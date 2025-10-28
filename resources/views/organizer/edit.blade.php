@extends('layouts.app') {{-- or your layout file --}}

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4 text-gray-100 flex justify-between"></h1>
    <a href="{{ route('organizer.events') }}"
        class="text-blue-400">Back to Events HUEHUEHUE</a>
</div>

<div class="container mt-5">
    <div class="card shadow-lg p-4">
        <h3 class="text-center text-primary mb-4">Edit Event</h3>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('organizer.updateEvent', $event->id) }}">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Title:</label>
                    <input type="text" name="title" value="{{ old('title', $event->title) }}" class="form-control" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Location:</label>
                    <input type="text" name="venue" value="{{ old('venue', $event->venue) }}" class="form-control">
                </div>

                <div class="col-md-6 mb-3">
                    <label>Start Date:</label>
                    <input type="datetime-local" name="start_date" value="{{ old('start_date', \Carbon\Carbon::parse($event->start_date)->format('Y-m-d\TH:i')) }}" class="form-control" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label>End Date:</label>
                    <input type="datetime-local" name="end_date" value="{{ old('end_date', \Carbon\Carbon::parse($event->end_date)->format('Y-m-d\TH:i')) }}" class="form-control" required>
                </div>

                <div class="col-md-12 mb-3">
                    <label>Description:</label>
                    <textarea name="description" rows="4" class="form-control">{{ old('description', $event->description) }}</textarea>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Status:</label>
                    <select name="status" class="form-select">
                        <option value="upcoming" {{ $event->status == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                        <option value="completed" {{ $event->status == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $event->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
            </div>

            <div class="text-center mt-3">
                <button class="btn btn-primary px-4">Update Event</button>
                <a href="{{ route('organizer.events') }}" class="btn btn-secondary px-4">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
