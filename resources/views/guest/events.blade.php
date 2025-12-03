@extends('layouts.app')
@section('title', 'My Invitations | EventNexus')
@section('styles')
<style>
    /* Event Image Container (Keeping the style block from the original for consistency) */
    .event-image-container {
        position: relative;
        width: 100%;
        max-width: 420px;
        aspect-ratio: 3 / 4;
        border-radius: 0.75rem;
        overflow: hidden;
        background-color: #f8f9fa;
        box-shadow: 0 0.5rem 1rem rgba(58, 53, 65, 0.15);
        transition: transform 0.3s ease;
    }
    .event-image-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .event-image-container:hover {
        transform: scale(1.02);
    }
    /* Fallback Text */
    .no-image-text {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(248, 249, 250, 0.85);
    color: #6c757d;
    font-size: 1.2rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    border-radius: 0.75rem;
    z-index: 1;
    pointer-events: none;
    }
    @media (max-width: 767.98px) {
        .event-image-container {
            max-width: 100%;
            aspect-ratio: 4 / 3;
        }
    }
</style>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- DataTable -->
    <div class="card">
        <div class="card-datatable table-responsive">
            <table class="datatables-basic table border-top">
                <thead>
                    <tr>
                        <th></th> <!-- Control column -->
                        <th>Title</th>
                        <th>Venue</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Invite Status</th>
                        <th>Details</th> <!-- View-Only Action -->
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    
    <!-- View Event Modal (Kept simple for Guest view) -->
    <div class="modal fade" id="viewEventModal" tabindex="-1" aria-labelledby="viewEventModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Event Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row align-items-start">
                        <!-- Image Section -->
                        <div class="col-xl-5 col-lg-5 col-md-6 col-sm-12 d-flex justify-content-center">
                            <div class="event-image-container">
                                <img id="event-image" src="" alt="Event Cover">
                                <div id="no-image-text" class="no-image-text d-none">No Image</div>
                            </div>
                        </div>

                        <!-- Details Section -->
                        <div class="col-md-7">
                            <h4 id="event-title" class="fw-bold mb-3 text-primary"></h4>

                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <div class="d-flex align-items-center">
                                        <i class="bx bx-user-pin text-primary me-2 fs-5"></i>
                                        <p class="mb-0"><strong>Client:</strong> <span id="event-client"></span></p>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="d-flex align-items-center">
                                        <i class="bx bx-map text-primary me-2 fs-5"></i>
                                        <p class="mb-0"><strong>Venue:</strong> <span id="event-venue"></span></p>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="d-flex align-items-center">
                                        <i class="bx bx-group text-primary me-2 fs-5"></i>
                                        <p class="mb-0"><strong>Capacity:</strong> <span id="event-capacity"></span></p>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="d-flex align-items-center">
                                        <i class="bx bx-time text-primary me-2 fs-5"></i>
                                        <p class="mb-0"><strong>Start:</strong> <span id="event-start"></span></p>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="d-flex align-items-center">
                                        <i class="bx bx-time-five text-primary me-2 fs-5"></i>
                                        <p class="mb-0"><strong>End:</strong> <span id="event-end"></span></p>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="d-flex align-items-center">
                                        <i class="bx bx-info-circle text-primary me-2 fs-5"></i>
                                        <p class="mb-0">
                                            <strong>Status:</strong>
                                            <span id="event-status" class="badge bg-label-info ms-1"></span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <h6 class="fw-bold text-secondary">Description</h6>
                            <p id="event-description" class="text-muted mt-2"></p>
                        </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>
@endsection

@section('scripts')
<script>
    //  Guest-specific AJAX URLs
    const GUEST_EVENTS_AJAX_URL = "/guest/getEvents"; 
    const GUEST_EVENT_DETAIL_URL = "/guest/events/"; 
    
    $(document).ready(function(){
        let dt_basic_table = $('.datatables-basic');
        let dt_basic;
        
        if (dt_basic_table.length) {
            dt_basic = dt_basic_table.DataTable({
                ajax: {
                    //  Guest Data Source
                    url: GUEST_EVENTS_AJAX_URL,
                    dataSrc: "data"
                },
                columns: [
                    { data: null }, 
                    { data: "title" },
                    { data: "venue" },
                    { data: "start_date" },
                    { data: "end_date" },
                    { data: "invitation_status" },
                    { data: null }
                ],
                columnDefs: [
                {
                    targets: 0,
                    className: "control",
                    orderable: false,
                    searchable: false,
                    responsivePriority: 1,
                    render: function () { return ""; },
                },
                {
                    // Title column
                    targets: 1,
                    responsivePriority: 2,
                    render: function (data, type, full) {
                        return `<span class="fw-semibold">${full.title}</span>`;
                    },
                },
                {
                    targets: 3, // Start Date
                    render: function (data) {
                        if (!data) return "";
                        return new Date(data).toLocaleString("en-US", { 
                            year: "numeric", month: "short", day: "numeric", 
                            hour: "2-digit", minute: "2-digit", hour12: true 
                        });
                    },
                },
                {
                    targets: 4, // End Date
                    render: function (data) {
                        if (!data) return "";
                        return new Date(data).toLocaleString("en-US", { 
                            year: "numeric", month: "short", day: "numeric", 
                            hour: "2-digit", minute: "2-digit", hour12: true 
                        });
                    },
                },
                {
                    targets: 5, // Invitation Status
                    render: function (data, type, full) {
                        const statusMap = {
                            pending: { title: "Pending", class: "bg-label-warning" },
                            accepted: { title: "Accepted", class: "bg-label-success" },
                            declined: { title: "Declined", class: "bg-label-danger" },
                            cancelled: { title: "Cancelled", class: "bg-label-secondary" },
                        };
                        const s = statusMap[full.invitation_status] || { title: full.invitation_status, class: "bg-label-secondary" };
                        return `<span class="badge ${s.class}">${s.title}</span>`;
                    },
                },
                {
                targets: -1,
                title: "Actions", 
                orderable: false,
                searchable: false,
                render: function (data, type, full) {
                    let actions = `<a href="javascript:void(0);" class="btn btn-sm btn-icon btn-label-secondary view-event-btn me-1" data-id="${full.id}" title="View Details">
                        <i class="bx bx-show"></i>
                    </a>`;
                    
                    // Show Accept/Decline buttons only if status is pending
                    if (full.invitation_status === 'pending') {
                        actions += `
                            <a href="javascript:void(0);" class="btn btn-sm btn-icon btn-success accept-invite-btn me-1" data-id="${full.id}" title="Accept Invitation">
                                <i class="bx bx-check"></i>
                            </a>
                            <a href="javascript:void(0);" class="btn btn-sm btn-icon btn-danger decline-invite-btn" data-id="${full.id}" title="Decline Invitation">
                                <i class="bx bx-x"></i>
                            </a>
                        `;
                    }
                    
                    // Show Cancel button if status is accepted (they can cancel their acceptance)
                    if (full.invitation_status === 'accepted' && full.status === 'upcoming') {
                        actions += `
                            <a href="javascript:void(0);" class="btn btn-sm btn-icon btn-warning cancel-invite-btn" data-id="${full.id}" title="Cancel Attendance">
                                <i class="bx bx-x-circle"></i>
                            </a>
                        `;
                    }
                    
                    // Show Re-accept button if status is declined (they can change their mind)
                    if (full.invitation_status === 'declined') {
                        actions += `
                            <a href="javascript:void(0);" class="btn btn-sm btn-icon btn-success accept-invite-btn" data-id="${full.id}" title="Accept Invitation">
                                <i class="bx bx-check"></i>
                            </a>
                        `;
                    }
                    
                    return actions;
                },
            }
            ],
                order: [[2, "desc"]],
                //  Removed all buttons (B) from the DOM structure
                dom: '<"card-header flex-column flex-md-row pb-0"<"head-label text-center">><"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end mt-n6 mt-md-0"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                displayLength: 7,
                lengthMenu: [7, 10, 25, 50, 75, 100],
                language: {
                    paginate: { next: '<i class="bx bx-chevron-right bx-18px"></i>', previous: '<i class="bx bx-chevron-left bx-18px"></i>' },
                },
                responsive: true,
            });
            $("div.head-label").html('<h5 class="card-title mb-0">My Invitations</h5>');
        }
    });

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
    url: `/guest/events/${eventId}`,
    method: "GET",
    success: function (event) {
      const start = new Date(event.start_date).toLocaleString("en-US", {
        year: "numeric",
        month: "short",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
        hour12: true,
      });
      const end = new Date(event.end_date).toLocaleString("en-US", {
        year: "numeric",
        month: "short",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
        hour12: true,
      });

      // Update modal
      $("#viewEventModal .modal-title").text(event.title);
      $("#event-title").text(event.title);
      $("#event-client").text(event.client?.full_name || "Unknown Client");
      $("#event-venue").text(event.venue);
      $("#event-capacity").text(event.capacity ? event.capacity + ' Guests' : 'Unlimited');
      if (event.capacity) {
            let accepted = event.accepted_count || 0; // Value from Controller
            let remaining = event.capacity - accepted;
            
            // Text formatting
            let seatsText = `${remaining} seats left`;
            
            // Logic: If full, show 'Sold Out' in red. If available, show green.
            if (remaining <= 0) {
                $("#event-seats-left")
                    .removeClass("text-success")
                    .addClass("text-danger")
                    .text("Full / Fully Booked");
            } else {
                $("#event-seats-left")
                    .removeClass("text-danger")
                    .addClass("text-success")
                    .text(seatsText);
            }
        } else {
            $("#event-seats-left").text("Unlimited");
        }
        
      $("#event-start").text(start);
      $("#event-end").text(end);
      $("#event-description").text(event.description ?? "No description provided.");

      // Update badge color
      const statusColors = {
        upcoming: "bg-label-info",
        ongoing: "bg-label-success",
        completed: "bg-label-primary",
        cancelled: "bg-label-danger",
      };
      const badgeClass = statusColors[event.status] || "bg-label-secondary";
      $("#event-status")
        .removeClass()
        .addClass(`badge ${badgeClass}`)
        .text(event.status.charAt(0).toUpperCase() + event.status.slice(1));

      /// IMAGE HANDLING
        const imagePath = event.cover_image 
            ? `/${event.cover_image}` 
            : null;

        if (imagePath) {
            $("#event-image")
                .show()
                .attr("src", imagePath);

            $("#no-image-text").addClass("d-none");
        } else {
            $("#event-image").hide();
            $("#no-image-text").removeClass("d-none");
        }
    },
    error: function () {
      $("#viewEventModal .modal-title").text("Error");
      $("#event-description").text("Failed to load event details. Please try again.");
    },
  });
});

    // Accept Invitation Handler
    $(document).on("click", ".accept-invite-btn", function () {
        const eventId = $(this).data("id");
        
        Swal.fire({
            title: 'Accept Invitation?',
            text: "You will be registered for this event.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, accept it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/guest/events/${eventId}/respond`,
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        status: "accepted"
                    },
                    success: function (response) {
                        Swal.fire({
                            title: 'Accepted!',
                            text: response.message,
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        });
                        
                        // Reload the DataTable
                        $('.datatables-basic').DataTable().ajax.reload();
                    },
                    error: function (xhr) {
                        Swal.fire('Error!', xhr.responseJSON?.message || 'Unable to accept invitation.', 'error');
                    }
                });
            }
        });
    });

    // Decline Invitation Handler
    $(document).on("click", ".decline-invite-btn", function () {
        const eventId = $(this).data("id");
        
        Swal.fire({
            title: 'Decline Invitation?',
            text: "You will not be attending this event.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, decline it'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/guest/events/${eventId}/respond`,
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        status: "declined"
                    },
                    success: function (response) {
                        Swal.fire({
                            title: 'Declined',
                            text: response.message,
                            icon: 'info',
                            timer: 1500,
                            showConfirmButton: false
                        });
                        
                        // Reload the DataTable
                        $('.datatables-basic').DataTable().ajax.reload();
                    },
                    error: function (xhr) {
                        Swal.fire('Error!', xhr.responseJSON?.message || 'Unable to decline invitation.', 'error');
                    }
                });
            }
        });
    });

    // Cancel Attendance Handler (for guests who already accepted)
    $(document).on("click", ".cancel-invite-btn", function () {
        const eventId = $(this).data("id");
        
        Swal.fire({
            title: 'Cancel Attendance?',
            text: "You will no longer be attending this event.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ff9f43',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, cancel attendance'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/guest/events/${eventId}/respond`,
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        status: "cancelled"
                    },
                    success: function (response) {
                        Swal.fire({
                            title: 'Cancelled',
                            text: response.message,
                            icon: 'info',
                            timer: 1500,
                            showConfirmButton: false
                        });
                        
                        // Reload the DataTable
                        $('.datatables-basic').DataTable().ajax.reload();
                    },
                    error: function (xhr) {
                        Swal.fire('Error!', xhr.responseJSON?.message || 'Unable to cancel attendance.', 'error');
                    }
                });
            }
        });
    });

</script>
@endsection