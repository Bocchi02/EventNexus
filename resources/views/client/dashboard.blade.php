@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    
    <div class="row">
        <div class="col-xxl-12 mb-6">
            <div class="card">
                <div class="d-flex row">
                    <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 ps-10 text-sm-center text-center">
                          <img src="../../assets/img/illustrations/boy-with-laptop-light.png" height="181" alt="Target User">
                        </div>
                    </div>
                    <div class="col-sm-7">
                        <div class="card-body">
                          <h5 class="card-title text-primary mb-3">Welcome back {{Auth::user()->firstname}}!</h5>
                          <p class="mb-6">
                            You have {{$upcomingEvents}} upcoming events. Break a leg!
                          </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-12 mb-6">
            <div class="card">
                <div class="card-widget-separator-wrapper">
                    <div class="card-body card-widget-separator">
                        <div class="row gy-4 gy-sm-1">
                            <div class="col-sm-6 col-lg-3">
                                <div class="d-flex justify-content-between align-items-center card-widget-1 border-end pb-4 pb-sm-0">
                                    <div>
                                        <h4 class="mb-0">{{$totalEvents}}</h4>
                                        <p class="mb-0">Total Events</p>
                                    </div>
                                    <div class="avatar me-sm-6">
                                        <span class="avatar-initial rounded bg-label-secondary">
                                            <i class="bx bx-calendar-event bx-26px"></i>
                                        </span>
                                    </div>
                                </div>
                                <hr class="d-none d-sm-block d-lg-none me-6">
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="d-flex justify-content-between align-items-center card-widget-2 border-end pb-4 pb-sm-0">
                                    <div>
                                        <h4 class="mb-0 text-success">{{$completedEvents}}</h4>
                                        <p class="mb-0 text-success">Completed Events</p>
                                    </div>
                                    <div class="avatar me-lg-6">
                                        <span class="avatar-initial rounded bg-label-success">
                                            <i class="bx bx-calendar-check bx-26px"></i>
                                        </span>
                                    </div>
                                </div>
                                <hr class="d-none d-sm-block d-lg-none">
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="d-flex justify-content-between align-items-center border-end pb-4 pb-sm-0 card-widget-3">
                                    <div>
                                        <h4 class="mb-0 text-primary">{{$upcomingEvents}}</h4>
                                        <p class="mb-0 text-primary">Upcoming Events</p>
                                    </div>
                                    <div class="avatar me-sm-6">
                                        <span class="avatar-initial rounded bg-label-primary text-heading">
                                            <i class="bx bx-calendar-week bx-26px"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h4 class="mb-0 text-danger">{{$cancelledEvents}}</h4>
                                        <p class="mb-0 text-danger">Cancelled Events</p>
                                    </div>
                                    <div class="avatar">
                                        <span class="avatar-initial rounded bg-label-danger">
                                            <i class="bx bx-calendar-x bx-26px"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> @if(isset($nextEvent) && $nextEvent)
    <div class="row">
    
        <div class="col-lg-4 mb-6">
            <div class="card h-100"> <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 card-title">Recent Activity</h5>
                    <small class="text-muted">Latest Acceptations</small>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse($recentActivities as $activity)
                            @php
                                $statusStyles = [
                                    'accepted' => [
                                        'icon' => 'bx-check', 
                                        'color' => 'success', 
                                        'text' => 'accepted invitation for'
                                    ],
                                    'declined' => [
                                        'icon' => 'bx-x', 
                                        'color' => 'danger', 
                                        'text' => 'declined invitation for'
                                    ],
                                    'cancelled' => [
                                        'icon' => 'bx-block', 
                                        'color' => 'secondary', 
                                        'text' => 'cancelled attendance for'
                                    ],
                                ];
                                
                                $style = $statusStyles[$activity->status] ?? $statusStyles['accepted'];
                            @endphp

                            <li class="list-group-item d-flex justify-content-between align-items-start px-0 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-3">
                                        <span class="avatar-initial rounded-circle bg-label-{{ $style['color'] }}">
                                            <i class='bx {{ $style['icon'] }}'></i>
                                        </span>
                                    </div>
                                    
                                    <div class="d-flex flex-column">
                                        <span class="fw-medium text-heading">
                                            {{ $activity->firstname }} {{ $activity->lastname }}
                                        </span>
                                        <small class="text-muted">
                                            {{ $style['text'] }} 
                                            <strong>{{ Str::limit($activity->event_title, 20) }}</strong>
                                        </small>
                                    </div>
                                </div>
                                
                                <small class="text-muted" style="font-size: 0.75rem;">
                                    {{ \Carbon\Carbon::parse($activity->updated_at)->diffForHumans() }}
                                </small>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted py-4">
                                No recent activities.
                            </li>
                        @endforelse
                    </ul>
                </div>
                @if(count($recentActivities) > 0)
                <div class="card-footer text-center border-top">
                    <a href="{{ url('/client/events') }}" class="small fw-semibold">View All Events</a>
                </div>
                @endif
            </div>
        </div>

        <div class="col-lg-8 mb-6">
            @if(isset($nextEvent) && $nextEvent)
                <div class="card h-100 overflow-hidden">
                    <div class="row g-0 h-100">
                        <div class="col-md-5 bg-label-primary d-flex align-items-center justify-content-center" 
                            style="min-height: 250px; background-image: url('{{ $nextEvent->cover_image ? asset($nextEvent->cover_image) : 'https://placehold.co/600x400/696cff/ffffff?text=No+Image' }}'); background-size: cover; background-position: center;">
                        </div>

                        <div class="col-md-7">
                            <div class="card-body p-4 d-flex flex-column justify-content-center h-100">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span class="badge bg-label-primary rounded-pill">
                                        {{ \Carbon\Carbon::parse($nextEvent->start_date)->format('M d, Y') }}
                                    </span>
                                    <small class="text-muted fw-bold">
                                        <i class="bx bx-time-five me-1"></i>
                                        {{ \Carbon\Carbon::parse($nextEvent->start_date)->format('h:i A') }}
                                    </small>
                                </div>

                                <h3 class="card-title fw-bold text-dark mb-2">{{ $nextEvent->title }}</h3>
                                
                                <p class="card-text text-muted mb-3 small">
                                    {{ Str::limit($nextEvent->description ?? 'No description provided.', 80) }}
                                </p>

                                <div class="p-2 bg-lighter rounded-3 mb-3 border">
                                    <div class="d-flex justify-content-around text-center" id="countdown-timer">
                                        <div>
                                            <h5 class="fw-bold text-primary mb-0" id="days">00</h5>
                                            <small class="text-muted" style="font-size: 0.65rem;">DAYS</small>
                                        </div>
                                        <div class="vr"></div>
                                        <div>
                                            <h5 class="fw-bold text-primary mb-0" id="hours">00</h5>
                                            <small class="text-muted" style="font-size: 0.65rem;">HRS</small>
                                        </div>
                                        <div class="vr"></div>
                                        <div>
                                            <h5 class="fw-bold text-primary mb-0" id="minutes">00</h5>
                                            <small class="text-muted" style="font-size: 0.65rem;">MINS</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    @php
                                        $capacity = $nextEvent->capacity ?? 100;
                                        $acceptedCount = $nextEvent->accepted_count ?? 0;
                                        $percentage = $capacity > 0 ? ($acceptedCount / $capacity) * 100 : 0;
                                    @endphp
                                    <div class="d-flex justify-content-between mb-1">
                                        <small class="fw-bold text-heading">Capacity</small>
                                        <small class="text-muted">{{ $acceptedCount }} / {{ $capacity }}</small>
                                    </div>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>

                                <button type="button" class="btn btn-outline-primary w-100 view-event-btn" data-id="{{ $nextEvent->id }}">
                                    Manage Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="card h-100 d-flex align-items-center justify-content-center p-5">
                    <div class="text-center">
                        <i class="bx bx-calendar-plus bx-lg text-muted mb-3"></i>
                        <h5>No Upcoming Events</h5>
                        <p class="text-muted">You have no scheduled events coming up.</p>
                        <a href="#" class="btn btn-primary">Create Event</a>
                    </div>
                </div>
            @endif
        </div>
    </div>
    @endif

</div>

<div class="modal fade" id="viewEventModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Event Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row align-items-start">
                    <div class="col-xl-5 col-lg-5 col-md-6 col-sm-12 d-flex justify-content-center">
                        <div class="event-image-container">
                            <img id="event-image" src="/images/no-image.png" alt="Event Cover">
                        </div>
                    </div>
                    <div class="col-md-7">
                        <h5 id="event-title" class="fw-bold mb-3"></h5>
                        <p><strong>Client:</strong> <span id="event-client"></span></p>
                        <p><strong>Venue:</strong> <span id="event-venue"></span></p>
                        <p><strong>Start:</strong> <span id="event-start"></span></p>
                        <p><strong>End:</strong> <span id="event-end"></span></p>
                        <p><strong>Status:</strong> <span id="event-status" class="badge bg-label-info"></span></p>
                        <p class="mt-3"><strong>Description:</strong></p>
                        <p id="event-description" class="text-muted"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<style>
  .event-image-container {
    position: relative;
    width: 100%;
    max-width: 420px;
    aspect-ratio: 3 / 4;
    border-radius: 0.75rem;
    overflow: hidden;
    background-color: #f8f9fa;
    box-shadow: 0 0.5rem 1rem rgba(58, 53, 65, 0.15);
  }
  .event-image-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get the event start date from Blade
        const eventDateStr = "{{ isset($nextEvent) ? $nextEvent->start_date : '' }}";
        
        if(eventDateStr) {
            const targetDate = new Date(eventDateStr).getTime();

            const timer = setInterval(function() {
                const now = new Date().getTime();
                const distance = targetDate - now;

                // Time calculations
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));

                // Display the result
                const daysEl = document.getElementById("days");
                const hoursEl = document.getElementById("hours");
                const minsEl = document.getElementById("minutes");

                if (daysEl) daysEl.innerText = days < 10 ? '0' + days : days;
                if (hoursEl) hoursEl.innerText = hours < 10 ? '0' + hours : hours;
                if (minsEl) minsEl.innerText = minutes < 10 ? '0' + minutes : minutes;

                // If the count down is finished
                if (distance < 0) {
                    clearInterval(timer);
                    const container = document.getElementById("countdown-timer");
                    if(container) container.innerHTML = "<h5 class='text-success mb-0'>Event Started!</h5>";
                }
            }, 1000);
        }
        
    $(document).on("click", ".view-event-btn", function () {
        const eventId = $(this).data("id");

        // Show loading state
        $("#viewEventModal .modal-title").text("Loading...");
        $("#event-title, #event-client, #event-venue, #event-start, #event-end, #event-status").text("");
        $("#event-description").text("Loading...");
        $("#event-image").attr("src", "").attr("alt", "Loading...");
        $("#viewEventModal").modal("show");

        // Fetch details
        $.ajax({
            url: `/client/events/${eventId}`,
            method: "GET",
            success: function (event) {
                const start = new Date(event.start_date).toLocaleString("en-US", { year: "numeric", month: "short", day: "numeric", hour: "2-digit", minute: "2-digit", hour12: true });
                const end = new Date(event.end_date).toLocaleString("en-US", { year: "numeric", month: "short", day: "numeric", hour: "2-digit", minute: "2-digit", hour12: true });

                // Update modal
                $("#viewEventModal .modal-title").text(event.title);
                $("#event-title").text(event.title);
                $("#event-client").text(event.client?.full_name || "Unknown Client");
                $("#event-venue").text(event.venue);
                $("#event-start").text(start);
                $("#event-end").text(end);
                $("#event-description").text(event.description ?? "No description provided.");

                // Update badge color
                const statusColors = { upcoming: "bg-label-info", ongoing: "bg-label-success", completed: "bg-label-primary", cancelled: "bg-label-danger" };
                const badgeClass = statusColors[event.status] || "bg-label-secondary";
                $("#event-status").removeClass().addClass(`badge ${badgeClass}`).text(event.status.charAt(0).toUpperCase() + event.status.slice(1));

                // Handle image (Use the logic from your events file)
                const imagePath = event.cover_image ? `/${event.cover_image}` : "/images/no-image.png";
                $("#event-image").attr("src", imagePath).attr("alt", event.title);
            },
            error: function () {
                $("#viewEventModal .modal-title").text("Error");
                $("#event-description").text("Failed to load event details.");
            }
        });
    });
  });
</script>
@endsection