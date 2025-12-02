@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        
        {{-- 1. WELCOME CARD --}}
        <div class="col-xxl-12 mb-6">
            <div class="card">
                <div class="d-flex row">
                    <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 ps-10 text-sm-center text-center">
                            <img src="{{ asset('assets/img/illustrations/boy-with-laptop-light.png') }}" height="181" alt="Guest Welcome">
                        </div>
                    </div>
                    <div class="col-sm-7">
                        <div class="card-body">
                            <h5 class="card-title text-primary mb-3">Welcome back, {{ Auth::user()->firstname }}!</h5>
                            <p class="mb-6">
                                You have <strong>{{ $upcomingEvents }}</strong> upcoming events on your schedule.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. PENDING INVITATIONS SECTION --}}
        @if($pendingInvites->count() > 0)
        <div class="col-12 mb-4 mt-2">
            <h5 class="text-warning fw-bold"><i class='bx bx-bell bx-tada'></i> Pending Invitations</h5>
            <div class="row">
                @foreach($pendingInvites as $invite)
                <div class="col-md-6 col-lg-12 mb-3" id="invite-card-{{ $invite->id }}">
                    <div class="card border border-warning shadow-none">
                        <div class="row g-0">
                            <div class="col-md-3 d-none d-md-block bg-label-warning d-flex align-items-center justify-content-center">
                                <img src="{{ $invite->cover_image ? asset($invite->cover_image) : 'https://placehold.co/200x200/ffab00/ffffff?text=Invite' }}" 
                                     class="img-fluid rounded-start h-100 object-fit-cover" style="max-height: 160px;" alt="...">
                            </div>
                            
                            <div class="col-md-9">
                                <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-md-center h-100">
                                    <div class="mb-3 mb-md-0">
                                        <div class="d-flex align-items-center mb-1">
                                            <span class="badge bg-label-warning me-2">Action Required</span>
                                            <small class="text-muted">{{ \Carbon\Carbon::parse($invite->start_date)->format('M d, Y @ h:i A') }}</small>
                                        </div>
                                        <h5 class="card-title mb-1">{{ $invite->title }}</h5>
                                        <p class="card-text text-muted small mb-0">
                                            Invited by: {{ $invite->client->firstname . ' ' .  $invite->client->lastname ?? 'Organizer' }}
                                        </p>
                                        <small class="text-muted"><i class='bx bx-map'></i> {{ $invite->venue }}</small>
                                    </div>

                                    <div>
                                        <button class="btn btn-outline-warning view-invite-btn" 
                                                data-id="{{ $invite->id }}">
                                            <i class='bx bx-envelope-open me-1'></i> Open Invitation
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        
        {{-- 3. MY TICKETS HEADER --}}
        <div class="col-12 mb-4 mt-2">
            <h5 class="text-primary fw-light">My Tickets</h5>
        </div>

        {{-- 4. TICKETS GRID --}}
        @forelse($myTickets as $event)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100">
                <div class="bg-label-primary d-flex align-items-center justify-content-center" 
                     style="height: 200px; overflow: hidden; border-radius: 0.5rem 0.5rem 0 0;">
                    <img src="{{ $event->cover_image ? asset($event->cover_image) : 'https://placehold.co/600x400/696cff/ffffff?text=Event' }}" 
                         alt="Event Image" class="w-100 h-100 object-fit-cover">
                </div>

                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge bg-label-primary">
                            {{ \Carbon\Carbon::parse($event->start_date)->format('M d, Y') }}
                        </span>
                        <small class="text-muted">
                            <i class="bx bx-time-five"></i> {{ \Carbon\Carbon::parse($event->start_date)->format('h:i A') }}
                        </small>
                    </div>

                    <h5 class="card-title mb-1 text-truncate" title="{{ $event->title }}">
                        {{ $event->title }}
                    </h5>
                    <p class="card-text small text-muted mb-3">
                        Invited by: {{ $event->client ? $event->client->firstname . " " .  $event->client->lastname : 'Client' }}
                    </p>

                    <div class="d-flex align-items-center mb-3 text-muted small">
                        <i class="bx bx-map me-2"></i>
                        <span class="text-truncate">{{ $event->venue ?? 'Venue TBD' }}</span>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-primary btn-sm view-ticket-btn" 
                                data-id="{{ $event->id }}">
                            View Ticket & Details
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card py-5">
                <div class="text-center">
                    <div class="mb-3">
                        <i class="bx bx-calendar-x text-muted" style="font-size: 4rem;"></i>
                    </div>
                    <h5>No Tickets Found</h5>
                    <p class="text-muted">You haven't accepted any event invitations yet.</p>
                </div>
            </div>
        </div>
        @endforelse
        
    </div>
</div>

{{-- MODAL 1: VIEW TICKET (For Accepted Events) --}}
<div class="modal fade" id="viewTicketModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Event Ticket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-5 text-center border-end">
                        <div class="mb-3 rounded overflow-hidden bg-light d-flex align-items-center justify-content-center" style="height: 400px;">
                            <img id="modal-event-image" src="" alt="Event Cover" class="w-100 h-100 object-fit-cover">
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="ps-md-3 mt-3 mt-md-0">
                            <span id="modal-event-status" class="badge bg-label-success mb-2">Confirmed</span>
                            <h3 id="modal-event-title" class="fw-bold mb-1">Event Title</h3>
                            <p class="text-muted mb-4">Hosted by: <span id="modal-event-host" class="fw-semibold">Organizer</span></p>

                            <div class="mb-3">
                                <label class="fw-bold text-uppercase small text-muted">Date & Time</label>
                                <p class="mb-0 fs-5 text-dark">
                                    <i class='bx bx-calendar me-2 text-primary'></i> <span id="modal-event-date">...</span>
                                </p>
                                <p class="mb-0 text-muted small ms-4 ps-1" id="modal-event-time">...</p>
                            </div>

                            <div class="mb-4">
                                <label class="fw-bold text-uppercase small text-muted">Venue</label>
                                <p class="mb-0 fs-5 text-dark">
                                    <i class='bx bx-map me-2 text-danger'></i> <span id="modal-event-venue">...</span>
                                </p>
                            </div>
                            <hr>
                            <div>
                                <label class="fw-bold text-uppercase small text-muted">Description</label>
                                <p id="modal-event-desc" class="text-muted small">...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-lighter">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL 2: PENDING INVITATION RESPONSE --}}
<div class="modal fade" id="respondModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-label-warning border-bottom-0 d-flex flex-column align-items-center pt-4">
                <div class="avatar avatar-xl mb-2">
                    <span class="avatar-initial rounded-circle bg-white text-warning">
                        <i class='bx bx-envelope bx-lg'></i>
                    </span>
                </div>
                <h4 class="modal-title fw-bold text-warning mt-2">You are invited!</h4>
                <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body text-center px-4 pb-4">
                <p class="text-muted mb-4">You have been invited to attend the following event:</p>
                
                <div class="card shadow-none bg-lighter border border-dashed mb-4">
                    <div class="card-body">
                        <h3 class="fw-bold text-dark mb-1" id="invite-modal-title">Loading...</h3>
                        <p class="text-muted mb-3">Hosted by <span id="invite-modal-host">...</span></p>
                        
                        <div class="d-flex justify-content-center gap-4 text-start d-inline-block">
                            <div>
                                <small class="text-uppercase fw-bold text-muted d-block">When</small>
                                <span class="fw-semibold" id="invite-modal-date">...</span>
                            </div>
                            <div class="vr"></div>
                            <div>
                                <small class="text-uppercase fw-bold text-muted d-block">Where</small>
                                <span class="fw-semibold" id="invite-modal-venue">...</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-3 rounded border mb-4 text-start mx-auto" style="max-width: 300px;">
                    <label class="form-label fw-bold small text-uppercase text-muted">Confirm Seats</label>
                    <div class="d-flex align-items-center">
                        <input type="number" id="invite-modal-seats" class="form-control form-control-lg me-2 text-center fw-bold" value="1" min="1">
                        <span class="text-muted small">
                            / <span id="invite-modal-max-seats">1</span> Allocated
                        </span>
                    </div>
                    <div class="form-text small mt-1 text-center">
                        You can reduce seats, but not exceed your allocation.
                    </div>
                </div>

                <p class="mb-3">Will you be joining us?</p>
                <div class="d-flex gap-2 justify-content-center">
                    <button class="btn btn-label-danger btn-lg w-45 respond-btn" id="btn-decline" data-status="declined">
                        Decline
                    </button>
                    <button class="btn btn-success btn-lg w-45 respond-btn" id="btn-accept" data-status="accepted">
                        <i class='bx bx-check me-1'></i> Accept
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Define Base URL for Images
        const BASE_URL = "{{ asset('/') }}";

        // 1. OPEN INVITATION MODAL
        $(document).on('click', '.view-invite-btn', function(e) {
            e.preventDefault(); 
            
            const eventId = $(this).data('id');

            if (!eventId) return;

            // Reset Modal
            $('#invite-modal-title').text('Loading...');
            $('#invite-modal-host').text('...');
            $('#invite-modal-date').text('...');
            $('#invite-modal-venue').text('...');
            $('#invite-modal-seats').val(1);    

            // Pass ID to buttons
            $('#btn-accept').attr('data-id', eventId);
            $('#btn-decline').attr('data-id', eventId);

            // Show Modal
            $('#respondModal').modal('show');

            // Fetch Data
            $.ajax({
                url: '/guest/events/' + eventId,
                type: 'GET',
                success: function(event) {
                    const dateStr = new Date(event.start_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                    const timeStr = new Date(event.start_date).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });

                    $('#invite-modal-title').text(event.title);
                    $('#invite-modal-venue').text(event.venue || 'TBD');
                    $('#invite-modal-date').text(`${dateStr} @ ${timeStr}`);
                    
                    if(event.organizer) {
                        $('#invite-modal-host').text(event.organizer.full_name);
                    }

                    // Handle Allocated Seats
                    const allocated = event.my_allocated_seats || 1;
                    $('#invite-modal-max-seats').text(allocated);
                    $('#invite-modal-seats').val(allocated).attr('max', allocated);
                },
                error: function() {
                    $('#invite-modal-title').text('Error loading details');
                }
            });
        });

        // 2. VIEW TICKET HANDLER
        $(document).on('click', '.view-ticket-btn', function() {
            const eventId = $(this).data('id');
            console.log("🎫 View Ticket Clicked:", eventId);
            
            $('#viewTicketModal').modal('show');
            
            // Reset Modal
            $('#modal-event-title').text('Loading...');
            $('#modal-event-image').attr('src', 'https://placehold.co/600x400/e0e0e0/ffffff?text=Loading');

            $.ajax({
                url: '/guest/events/' + eventId,
                type: 'GET',
                success: function(response) {
                    const startDate = new Date(response.start_date);
                    const dateStr = startDate.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
                    const timeStr = startDate.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });

                    $('#modal-event-title').text(response.title);
                    $('#modal-event-venue').text(response.venue || 'To be announced');
                    $('#modal-event-date').text(dateStr);
                    $('#modal-event-time').text(timeStr);
                    $('#modal-event-desc').text(response.description || 'No description provided.');
                    
                    if(response.organizer) {
                        $('#modal-event-host').text(response.organizer.full_name);
                    }

                    // FIX: Use BASE_URL + cover_image
                    if(response.cover_image) {
                        // If cover_image already starts with "uploads/", we just append it to BASE_URL
                        $('#modal-event-image').attr('src', BASE_URL + response.cover_image);
                    } else {
                        $('#modal-event-image').attr('src', 'https://placehold.co/600x400/696cff/ffffff?text=Event');
                    }
                }
            });
        });

        // 3. RESPOND HANDLER
        $(document).on('click', '.respond-btn', function() {
            const btn = $(this);
            const eventId = btn.attr('data-id');
            const status = btn.data('status');
            const seats = $('#invite-modal-seats').val();

            if(!eventId) return;

            $('.respond-btn').prop('disabled', true);

            $.ajax({
                url: `/guest/events/${eventId}/respond`,
                type: 'POST',
                data: {
                    status: status,
                    seats_confirmed: seats,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#respondModal').modal('hide');
                    
                    if(status === 'accepted') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Invitation Accepted!',
                            text: 'Event added to your tickets.',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Declined', 'Invitation declined.', 'info').then(() => {
                            location.reload();
                        });
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Something went wrong.', 'error');
                    $('.respond-btn').prop('disabled', false);
                }
            });
        });
    });
</script>
@endsection