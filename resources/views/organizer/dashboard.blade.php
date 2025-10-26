@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <h1 class="mb-3">Organizer Dashboard</h1>
            <p>Welcome, {{ Auth::user()->name }}!</p>
            <p>This is your dashboard area for managing events.</p>
        </div>
    </div>
</div>
@endsection
