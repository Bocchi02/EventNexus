@extends('layouts.guest')

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card p-4 text-center shadow-lg" style="max-width: 500px;">
        <div class="mb-3 text-success">
            <i class="bx bx-check-circle" style="font-size: 4rem;"></i>
        </div>
        <h3>You're going to {{ $event_title }}!</h3>
        
        @if($is_new_user ?? false)
            <p class="text-muted">Your account has been created successfully.</p>
            
            <div class="alert alert-primary text-start">
                <p class="mb-1"><strong>Email:</strong> {{ $email }}</p>
                <p class="mb-0"><strong>Password:</strong> <code>{{ $password }}</code></p>
            </div>
            
            <div class="alert alert-warning text-start small">
                <i class="bx bx-info-circle me-1"></i>
                <strong>Important:</strong> Please save these credentials. You can change your password in your profile settings.
            </div>
        @else
            <p class="text-muted">You have successfully accepted the invitation using your existing account.</p>
        @endif
        
        <a href="{{ route('guest.dashboard') }}" class="btn btn-primary btn-lg w-100 mt-3">
            <i class="bx bx-home me-2"></i> Go to My Dashboard
        </a>
    </div>
</div>
@endsection