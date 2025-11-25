@extends('layouts.app')
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
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Dashboard /</span> My Invitations
    </h4>
    
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
                        <th>Status</th>
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
                                <img id="event-image-guest" src="/images/no-image.png" alt="Event Cover">
                            </div>
                        </div>

                        <!-- Details Section -->
                        <div class="col-md-7">
                            <h5 id="event-title-guest" class="fw-bold mb-3"></h5>
                            {{-- Changed Client to Organizer --}}
                            <p><strong>Organizer:</strong> <span id="event-organizer-guest"></span></p> 
                            <p><strong>Venue:</strong> <span id="event-venue-guest"></span></p>
                            <p><strong>Start:</strong> <span id="event-start-guest"></span></p>
                            <p><strong>End:</strong> <span id="event-end-guest"></span></p>
                            <p><strong>Status:</strong> <span id="event-status-guest" class="badge bg-label-info"></span></p>
                            <p class="mt-3"><strong>Description:</strong></p>
                            <p id="event-description-guest" class="text-muted"></p>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
</div>
@endsection

@section('scripts')
<script>
    // 💡 Guest-specific AJAX URLs
    const GUEST_EVENTS_AJAX_URL = "/guest/getEvents"; 
    const GUEST_EVENT_DETAIL_URL = "/guest/events/"; 
    
    $(document).ready(function(){
        let dt_basic_table = $('.datatables-basic');
        let dt_basic;
        
        if (dt_basic_table.length) {
            dt_basic = dt_basic_table.DataTable({
                ajax: {
                    // 🚀 Guest Data Source
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
                    if (full.invitation_status === 'accepted') {
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
                // 🚀 Removed all buttons (B) from the DOM structure
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

    // 💡 Guest-side Modal Handler for View Details
    $(document).on("click", ".view-event-btn", function () {
        const eventId = $(this).data("id");

        // Show loading state
        $("#viewEventModal .modal-title").text("Loading...");
        $("#event-title-guest, #event-organizer-guest, #event-venue-guest, #event-start-guest, #event-end-guest, #event-status-guest").text("");
        $("#event-description-guest").text("Loading...");
        $("#event-image-guest").attr("src", "").attr("alt", "Loading...");
        $("#viewEventModal").modal("show");

        // Fetch details from the guest endpoint
        $.ajax({
            url: GUEST_EVENT_DETAIL_URL + eventId, // Assumes a route like /guest/events/{id}
            method: "GET",
            success: function (event) {
                // Ensure the dates exist before trying to format
                const start = event.start_date ? new Date(event.start_date).toLocaleString("en-US", { year: "numeric", month: "short", day: "numeric", hour: "2-digit", minute: "2-digit", hour12: true }) : 'N/A';
                const end = event.end_date ? new Date(event.end_date).toLocaleString("en-US", { year: "numeric", month: "short", day: "numeric", hour: "2-digit", minute: "2-digit", hour12: true }) : 'N/A';

                // Update modal
                $("#viewEventModal .modal-title").text(event.title);
                $("#event-title-guest").text(event.title);
                // The organizer data must be eager loaded in the controller
                $("#event-organizer-guest").text(event.organizer?.full_name || "Organizer Not Found"); 
                $("#event-venue-guest").text(event.venue);
                $("#event-start-guest").text(start);
                $("#event-end-guest").text(end);
                $("#event-description-guest").text(event.description ?? "No description provided.");

                // Update status badge
                const statusColors = { upcoming: "bg-label-info", ongoing: "bg-label-success", completed: "bg-label-primary", cancelled: "bg-label-danger" };
                const badgeClass = statusColors[event.status] || "bg-label-secondary";
                $("#event-status-guest")
                    .removeClass()
                    .addClass(`badge ${badgeClass}`)
                    .text(event.status.charAt(0).toUpperCase() + event.status.slice(1));

                // Handle image
                const imagePath = event.cover_image ? `/${event.cover_image}` : "/images/no-image.png";
                $("#event-image-guest").attr("src", imagePath).attr("alt", event.title);
            },
            error: function () {
                $("#viewEventModal .modal-title").text("Error");
                $("#event-description-guest").text("Failed to load event details. Please try again.");
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