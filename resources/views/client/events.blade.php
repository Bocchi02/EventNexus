@extends('layouts.app')
@section('styles')
<style>    /* Event Image Container */
.event-image-container {
  position: relative;
  width: 100%;
  max-width: 420px; /* approximate portrait letter size in modal-xl */
  aspect-ratio: 3 / 4; /* maintains portrait look */
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

/* Make it smaller on smaller modals or mobile screens */
@media (max-width: 1199.98px) {
  .event-image-container {
    max-width: 320px;
  }
}

@media (max-width: 767.98px) {
  .event-image-container {
    max-width: 100%;
    aspect-ratio: 4 / 3; /* more landscape for small screens */
  }
}
</style>
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- DataTable with Buttons -->
              <div class="card">
                <div class="card-datatable table-responsive">
                  <table class="datatables-basic table border-top">
                    <thead>
                    <tr>
                        <th></th> <!-- potang th yan -->
                        <th>Title</th>
                        <th>Venue</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                  </table>
                </div>
              </div>
              <!-- View Event Modal -->
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
                            <img id="event-image" src="/images/no-image.png" alt="Event Cover">
                            </div>
                        </div>

                        <!-- Details Section -->
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
                <!-- Invite Guest Modal -->
                <div class="modal fade" id="inviteGuestModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-md">
                    <div class="modal-content">
                    <form id="inviteGuestForm">
                        @csrf
                        <div class="modal-header">
                        <h5 class="modal-title">Invite Guest</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                        <input type="hidden" id="event_id" name="event_id">

                        <div class="row">
                            <div class="col-md-4 mb-3">
                            <label class="form-label">First Name</label>
                            <input type="text" class="form-control" name="firstname" required>
                            </div>

                            <div class="col-md-4 mb-3">
                            <label class="form-label">Middle Name</label>
                            <input type="text" class="form-control" name="middlename">
                            </div>

                            <div class="col-md-4 mb-3">
                            <label class="form-label">Last Name</label>
                            <input type="text" class="form-control" name="lastname" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Guest Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>

                        <div class="alert alert-secondary small">
                            A guest account will automatically be created if no account exists for this email.
                        </div>
                        </div>

                        <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" id="sendInviteBtn" class="btn btn-primary">Send Invitation</button>
                        </div>

                    </form>
                    </div>
                </div>
                </div>

                <!-- View Guests List Modal -->
                <div class="modal fade" id="viewGuestsModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg"> <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="viewGuestsTitle">Guests for Event: </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        
                        <div class="modal-body">
                            <ul class="nav nav-tabs mb-3" id="guestTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active text-success" id="accepted-tab" data-bs-toggle="tab" data-bs-target="#accepted-pane" type="button" role="tab">
                                        ✓ Accepted 
                                        <span class="badge bg-success-subtle text-success ms-1" id="count-accepted">0</span>
                                    </button>
                                </li>

                                <li class="nav-item" role="presentation">
                                    <button class="nav-link text-warning" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending-pane" type="button" role="tab">
                                        ⏳ Pending 
                                        <span class="badge bg-warning-subtle text-warning ms-1" id="count-pending">0</span>
                                    </button>
                                </li>

                                <li class="nav-item" role="presentation">
                                    <button class="nav-link text-danger" id="declined-tab" data-bs-toggle="tab" data-bs-target="#declined-pane" type="button" role="tab">
                                        ✗ Declined 
                                        <span class="badge bg-danger-subtle text-danger ms-1" id="count-declined">0</span>
                                    </button>
                                </li>

                                <li class="nav-item" role="presentation">
                                    <button class="nav-link text-secondary" id="cancelled-tab" data-bs-toggle="tab" data-bs-target="#cancelled-pane" type="button" role="tab">
                                        ⊗ Cancelled 
                                        <span class="badge bg-secondary-subtle text-secondary ms-1" id="count-cancelled">0</span>
                                    </button>
                                </li>
                            </ul>

                            <div class="tab-content" id="guestTabsContent">
                                
                                <div class="tab-pane fade show active" id="accepted-pane" role="tabpanel" tabindex="0">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover" id="acceptedGuestsTable">
                                            <thead>
                                                <tr><th>Name</th><th>Email</th><th>Status</th></tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="pending-pane" role="tabpanel" tabindex="0">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover" id="pendingGuestsTable">
                                            <thead>
                                                <tr><th>Name</th><th>Email</th><th>Status</th></tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="declined-pane" role="tabpanel" tabindex="0">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover" id="declinedGuestsTable">
                                            <thead>
                                                <tr><th>Name</th><th>Email</th><th>Status</th></tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="cancelled-pane" role="tabpanel" tabindex="0">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover" id="cancelledGuestsTable">
                                            <thead>
                                                <tr><th>Name</th><th>Email</th><th>Status</th></tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
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
    $(document).ready(function(){
    var dt_basic_table = $('.datatables-basic'), dt_basic;
    if (dt_basic_table.length) {
    dt_basic = dt_basic_table.DataTable({
        ajax: {
                url: "/client/getEvents",
                dataSrc: "data"
            },
        columns: [
                { data: null }, // for control column
                { data: "title" },
                { data: "venue" },
                { data: "start_date" },
                { data: "end_date" },
                { data: "status" },
                { data: null } // actions column    
            ],  
      columnDefs: [
                {
                    targets: 0,
                    className: "control",
                    orderable: false,
                    searchable: false,
                    responsivePriority: 1,
                    render: function () {
                        return "";
                    },
                },
                {
                    // Title & client name
                    targets: 1,
                    responsivePriority: 2,
                    render: function (data, type, full) {
                        return `
                            <div class="d-flex flex-column">
                                <span class="fw-semibold">${full.title}</span>
                                <small class="text-muted">Client: ${full.client}</small>
                            </div>`;
                    },
                },
                {
                    targets: 3, // Start Date
                    render: function (data) {
                        if (!data) return "";
                        const start = new Date(data).toLocaleString("en-US", {
                        year: "numeric",
                        month: "short",
                        day: "numeric",
                        hour: "2-digit",
                        minute: "2-digit",
                        hour12: true,
                        });
                        return start;
                    },
                },
                {
                    targets: 4, // End Date
                    render: function (data) {
                        if (!data) return "";
                        const end = new Date(data).toLocaleString("en-US", {
                        year: "numeric",
                        month: "short",
                        day: "numeric",
                        hour: "2-digit",
                        minute: "2-digit",
                        hour12: true,
                        });
                        return end;
                    },
                },
                {
                    targets: 5,
                    render: function (data, type, full) {
                        const statusMap = {
                            completed: { title: "Completed", class: "bg-label-primary" },
                            cancelled: { title: "Cancelled", class: "bg-label-danger" },
                            upcoming: { title: "Upcoming", class: "bg-label-info" },
                        };
                        const s = statusMap[full.status] || { title: full.status, class: "bg-label-secondary" };
                        return `<span class="badge ${s.class}">${s.title}</span>`;
                    },
                },
                {
                    targets: -1,
                    title: "Actions",
                    orderable: false,
                    searchable: false,
                    render: function (data, type, full) {
                        const deleteUrl = `/organizer/events/${full.id}`;

                        // 🧠 Check if event is already cancelled or completed
                        const isCancelled = full.status === "cancelled" || full.status === "completed";
                        const cancelClass = isCancelled ? "text-muted disabled" : "cancel-event-btn text-danger";
                        const cancelText = isCancelled ? "Already Cancelled" : "Cancel";

                        return `
                        <div class="d-inline-block">
                            <a href="javascript:;" class="btn btn-icon dropdown-toggle hide-arrow me-1" data-bs-toggle="dropdown">
                            <i class="bx bx-dots-vertical-rounded bx-md"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end m-0">
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item view-event-btn" data-id="${full.id}">
                                View
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item invite-guest-btn" data-event-id="${full.id}">
                                Invite Guests
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item view-guests-btn" data-event-id="${full.id}"> 
                                View Guests
                                </a>
                            </li>
                        </div>`;
                    },//gagi watashit man
                }

            ],
            order: [[2, "desc"]],
                dom: '<"card-header flex-column flex-md-row pb-0"<"head-label text-center"><"dt-action-buttons text-end pt-6 pt-md-0"B>><"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end mt-n6 mt-md-0"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                displayLength: 7,
                lengthMenu: [7, 10, 25, 50, 75, 100],
                language: {
                    paginate: {
                        next: '<i class="bx bx-chevron-right bx-18px"></i>',
                        previous: '<i class="bx bx-chevron-left bx-18px"></i>',
                    },
                },
                buttons: [
                    
                ],
                responsive: {
                    details: {
                        display: $.fn.dataTable.Responsive.display.modal({
                            header: function (row) {
                                var data = row.data();
                                return "Details of " + data["title"];
                            },
                        }),
                        type: "column",
                        renderer: function (api, rowIdx, columns) {
                            var data = $.map(columns, function (col, i) {
                                return col.title !== "" 
                                    ? '<tr data-dt-row="' +
                                          col.rowIndex +
                                          '" data-dt-column="' +
                                          col.columnIndex +
                                          '">' +
                                          "<td>" +
                                          col.title +
                                          ":" +
                                          "</td> " +
                                          "<td>" +
                                          col.data +
                                          "</td>" +
                                          "</tr>"
                                    : "";
                            }).join("");

                            return data
                                ? $('<table class="table"/><tbody />').append(data)
                                : false;
                        },
                    },
                },
            });
            $("div.head-label").html('<h5 class="card-title mb-0">My Events</h5>');
    }
   });
   
   //view event details
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

        // Handle image
        const imagePath = event.cover_image
            ? `/${event.cover_image}`
            : "/images/no-image.png";
        $("#event-image").attr("src", imagePath).attr("alt", event.title);
        },
        error: function () {
        $("#viewEventModal .modal-title").text("Error");
        $("#event-description").text("Failed to load event details. Please try again.");
        },
    });
    });

    $(document).on("click", ".invite-guest-btn", function () {
        currentEventId = $(this).data("event-id");

        $("#inviteGuestModal").modal("show");
    });


    let currentEventId = null;

    // Send invitation
    $("#inviteGuestForm").on("submit", function (e) {
        e.preventDefault();

        if (!currentEventId) {
            Swal.fire("Error!", "Event ID is missing!", "error");
            return;
        }

        let formData = new FormData(this);

        $.ajax({
            url: `/client/events/${currentEventId}/invite`,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                $("#inviteGuestModal").modal("hide");

                Swal.fire({
                    title: "Invitation Sent",
                    text: response.message,
                    icon: "success",
                    timer: 1500,
                    showConfirmButton: false
                });
            },
            error: function (xhr) {
                Swal.fire("Error!", xhr.responseJSON?.message || "Unable to send invitation.", "error");
            }
        });
    });

    // View Guests List Handler
    $(document).on("click", ".view-guests-btn", function () {
        const eventId = $(this).data("event-id");

        // Clear previous data
        $("#viewGuestsTitle").text("Guests for Event: Loading...");
        $("#acceptedGuestsTable tbody").empty();
        $("#pendingGuestsTable tbody").empty();
        $("#declinedGuestsTable tbody").empty();
        $("#cancelledGuestsTable tbody").empty();

        // ---------------------------------------------------------
        // NEW: Reset the tab to the first one (Accepted) automatically
        // ---------------------------------------------------------
        var firstTabEl = document.querySelector('#guestTabs button[data-bs-target="#accepted-pane"]');
        var firstTab = new bootstrap.Tab(firstTabEl);
        firstTab.show();
        // ---------------------------------------------------------

        $("#viewGuestsModal").modal("show");

        $.ajax({
            url: `/client/events/${eventId}/guests`, 
            method: "GET",
            success: function (response) {
                $("#viewGuestsTitle").text(`Guests for Event: ${response.eventTitle}`);
                
                $("#count-accepted").text(response.accepted ? response.accepted.length : 0);
                $("#count-pending").text(response.pending ? response.pending.length : 0);
                $("#count-declined").text(response.declined ? response.declined.length : 0);
                $("#count-cancelled").text(response.cancelled ? response.cancelled.length : 0);
                
                // --- Accepted Guests ---
                if (response.accepted && response.accepted.length > 0) {
                    response.accepted.forEach(guest => {
                        const row = `
                            <tr>
                                <td>${guest.full_name}</td> 
                                <td>${guest.email}</td>
                                <td><span class="badge bg-success">Accepted</span></td>
                            </tr>`;
                        $("#acceptedGuestsTable tbody").append(row);
                    });
                } else {
                    $("#acceptedGuestsTable tbody").append('<tr><td colspan="3" class="text-center text-muted">No accepted guests yet.</td></tr>');
                }

                // --- Pending Invitations ---
                if (response.pending && response.pending.length > 0) {
                    response.pending.forEach(invite => {
                        const row = `
                            <tr>
                                <td>${invite.full_name}</td>
                                <td>${invite.email}</td>
                                <td><span class="badge bg-warning">Pending</span></td>
                            </tr>`;
                        $("#pendingGuestsTable tbody").append(row);
                    });
                } else {
                    $("#pendingGuestsTable tbody").append('<tr><td colspan="3" class="text-center text-muted">No pending invitations.</td></tr>');
                }

                // --- Declined Guests ---
                if (response.declined && response.declined.length > 0) {
                    response.declined.forEach(guest => {
                        const row = `
                            <tr>
                                <td>${guest.full_name}</td>
                                <td>${guest.email}</td>
                                <td><span class="badge bg-danger">Declined</span></td>
                            </tr>`;
                        $("#declinedGuestsTable tbody").append(row);
                    });
                } else {
                    $("#declinedGuestsTable tbody").append('<tr><td colspan="3" class="text-center text-muted">No declined invitations.</td></tr>');
                }

                // --- Cancelled Guests ---
                if (response.cancelled && response.cancelled.length > 0) {
                    response.cancelled.forEach(guest => {
                        const row = `
                            <tr>
                                <td>${guest.full_name}</td>
                                <td>${guest.email}</td>
                                <td><span class="badge bg-secondary">Cancelled</span></td>
                            </tr>`;
                        $("#cancelledGuestsTable tbody").append(row);
                    });
                } else {
                    $("#cancelledGuestsTable tbody").append('<tr><td colspan="3" class="text-center text-muted">No cancelled attendance.</td></tr>');
                }
            },
            error: function () {
                $("#viewGuestsTitle").text("Error loading guest list.");
            }
        });
    });


</script>
@endsection