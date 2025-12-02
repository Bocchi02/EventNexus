@extends('layouts.app')
@section('title', 'My Events | EventNexus')
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
@endsection
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- DataTable with Buttons -->
              <div class="card">
                <div class="card-datatable table-responsive">
                  <table class="datatables-basic table border-top">
                    <thead>
                    <tr>
                        <th></th> 
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
              <!-- Modal to add new record -->
              <!-- Modal -->
            <div class="modal fade" id="createNewEventModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <form id="addNewEventForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel1">Create New Event</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col mb-4">
                                <label for="title" class="form-label">Event Title</label>
                                <input type="text" name="title" class="form-control" placeholder="Enter Event Title">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-6">
                                <label for="client_id" class="form-label">Client</label>
                                <select name="client_id" class="form-select" required>
                                    <option value="" selected disabled>-- Select Client --</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}">
                                            {{ ucfirst($client->full_name) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-4">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" name="description" rows="3" placeholder="Enter event description"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-4">
                                <label for="venue" class="form-label">Venue</label>
                                <input type="text" name="venue" class="form-control" placeholder="Enter Venue">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-4">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input class="form-control" type="datetime-local" name="start_date" id="start_date"/>
                            </div>
                            <div class="col mb-4">
                                <label for="end_date" class="form-label">End Date</label>
                                <input class="form-control" type="datetime-local" name="end_date" id="end_date"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-4">
                                <label for="cover_image" class="form-label">Cover Image (Optional)</label>
                                <input class="form-control" type="file" name="cover_image" accept="image/*">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-4">
                                <label for="capacity" class="form-label">Guest Capacity</label>
                                <input type="number" name="capacity" id="capacity" class="form-control" placeholder="Ex: 100" min="1" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" id="addEventBtn" class="btn btn-primary">Create Event</button>
                    </div>
                </form>
                </div>
            </div>
            </div>

            <!-- Modal to edit event record huehue -->
            <div class="modal fade" id="editEventModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form id="editEventForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="edit_event_id" name="id">
                        
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Event</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        
                        <div class="modal-body">
                            <div class="row">
                                <div class="col mb-4">
                                    <label for="edit_title" class="form-label">Event Title</label>
                                    <input type="text" id="edit_title" name="title" class="form-control" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col mb-6">
                                    <label for="edit_client_id" class="form-label">Client</label>
                                    <select id="edit_client_id" name="client_id" class="form-select" required>
                                        <option value="" disabled>-- Select Client --</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}">{{ ucfirst($client->full_name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col mb-4">
                                    <label for="edit_description" class="form-label">Description</label>
                                    <textarea id="edit_description" name="description" class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col mb-4">
                                    <label for="edit_venue" class="form-label">Venue</label>
                                    <input type="text" id="edit_venue" name="venue" class="form-control" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col mb-4">
                                    <label for="edit_start_date" class="form-label">Start Date</label>
                                    <input type="datetime-local" id="edit_start_date" name="start_date" class="form-control" required>
                                </div>
                                <div class="col mb-4">
                                    <label for="edit_end_date" class="form-label">End Date</label>
                                    <input type="datetime-local" id="edit_end_date" name="end_date" class="form-control" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col mb-4">
                                    <label for="edit_capacity" class="form-label">Guest Capacity</label>
                                    <input type="number" id="edit_capacity" name="capacity" class="form-control" min="1" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col mb-4">
                                    <label for="edit_cover_image" class="form-label">Change Cover Image (Optional)</label>
                                    <input type="file" id="edit_cover_image" name="cover_image" class="form-control" accept="image/*">
                                    <div class="form-text">Leave empty to keep the current image.</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="modal-footer">
                            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
            <!-- Modal to view record -->
              <!-- Modal -->
            <!-- Modal -->
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
                                        <i class="bx bx-chair text-primary me-2 fs-5"></i>
                                        <p class="mb-0">
                                            <strong>Available Seats:</strong>
                                            <span id="event-seats-left" class="fw-bold text-success"></span>
                                        </p>
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
@endsection

@section('scripts')
<script>
   $(document).ready(function(){
    var dt_basic_table = $('.datatables-basic'), dt_basic;
    if (dt_basic_table.length) {
    dt_basic = dt_basic_table.DataTable({
        ajax: {
                url: "/organizer/getEvents",
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
                            ongoing: { title: "Ongoing", class: "bg-label-success"}
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
                        // Check if event is already cancelled or completed
                        const isCancelled = full.status === "cancelled" || full.status === "completed" || full.status === "ongoing";
                        const cancelClass = isCancelled ? "text-muted disabled" : "cancel-event-btn text-warning";
                        const cancelText = isCancelled ? "Not Allowed" : "Cancel";
                        const isDisabled = full.status === "cancelled" || full.status === "completed" || full.status === "ongoing";

                        // Disable edit for ongoing, completed, cancelled
                        const disableEdit =
                            full.status === "ongoing" ||
                            full.status === "completed" ||
                            full.status === "cancelled";

                        // Attributes for edit button
                        const editAttributes = disableEdit
                            ? `class="btn btn-icon item-edit editEventBtn text-muted" style="pointer-events:none;"`
                            : `class="btn btn-icon item-edit editEventBtn" 
                            data-id="${full.id}" 
                            data-bs-toggle="modal" 
                            data-bs-target="#editEventModal"`;

                        return `
                        <div class="d-inline-block">
                            <a href="javascript:;" class="btn btn-icon dropdown-toggle hide-arrow me-1" data-bs-toggle="dropdown">
                                <i class="bx bx-dots-vertical-rounded bx-md"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end m-0">
                                <li>
                                    <a href="javascript:void(0);" class="dropdown-item view-event-btn" data-id="${full.id}">
                                        <i class="bx bx-show me-1"></i> View
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="dropdown-item ${cancelClass}" data-id="${full.id}" data-status="${full.status}">
                                        <i class="bx bx-block me-1"></i> ${cancelText}
                                    </a>
                                </li>
                                <div class="dropdown-divider"></div>
                                <a href="javascript:void(0);" 
                                    class="dropdown-item text-danger delete-event-btn ${isDisabled ? "disabled text-muted" : ""}" 
                                    data-id="${full.id}">
                                    <i class="bx bx-trash me-1"></i> ${isDisabled ? "Not Allowed" : "Delete"}
                                </a>
                            </ul>
                        </div>

                        <a href="javascript:;" data-id="${full.id}" ${editAttributes}>
                            <i class="bx bx-edit bx-md"></i>
                        </a>
                        `;
                    },
                }

            ],
            order: [[3, "desc"]],
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
                    {
                        text: '<i class="bx bx-plus bx-sm me-sm-2"></i> <span class="d-none d-sm-inline-block">Create New Event</span>',
                        className: "create-new btn btn-primary",
                        attr: {
                            type: "button",
                            "data-bs-toggle": "modal",
                            "data-bs-target": "#createNewEventModal",
                        },
                    },
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
            $("div.head-label").html('<h5 class="card-title mb-0">All Events</h5>');
    }
   });
    $(document).ready(function () {
    $("#addEventBtn").on("click", function (e) {
        e.preventDefault();

        let formData = new FormData($("#addNewEventForm")[0]);

        $.ajax({
            url: "{{ route('organizer.storeEvent') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            success: function (response) {
                $("#createNewEventModal").modal("hide");
                Swal.fire({
                    title: "Success!",
                    text: response.message || "Event created successfully.",
                    icon: "success",
                    timer: 1500,
                    showConfirmButton: false,
                });
                $('.datatables-basic').DataTable().ajax.reload();
            },
            error: function (xhr) {
                let errors = xhr.responseJSON?.errors;
                let msg = "";
                $.each(errors, function (key, value) {
                    msg += value[0] + "\n";
                });
                Swal.fire("Error!", msg, "error");
            },
        });
    });
});



document.addEventListener("DOMContentLoaded", function() {
    const now = new Date();
    const formatted = now.toISOString().slice(0, 16); // format: "YYYY-MM-DDTHH:mm"
    const end = new Date(now.getTime() + 60 * 60 * 1000);
    document.getElementById("start_date").value = formatted;
    document.getElementById("end_date").value = end.toISOString().slice(0, 16);
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
    url: `/organizer/events/${eventId}`,
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

// Global CSRF setup
$.ajaxSetup({
  headers: {
    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
  }
});

// Cancel Event Handler
$(document).on("click", ".cancel-event-btn", function () {
  const eventId = $(this).data("id");
  const status = $(this).data("status");

  // Prevent cancelling already cancelled or completed events
  if (status === "cancelled" || status === "completed") {
    Swal.fire({
      title: "Not Allowed",
      text: `This event is already ${status}.`,
      icon: "info",
      confirmButtonColor: "#3085d6",
    });
    return;
  }

  // Confirm cancellation
  Swal.fire({
    title: "Cancel this event?",
    text: "This action will mark the event as 'Cancelled'.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#6c757d",
    confirmButtonText: "Yes, cancel it",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: `/organizer/events/${eventId}/cancel`,
        method: "POST",
        success: function (response) {
          Swal.fire({
            title: "Event Cancelled",
            text: response.message,
            icon: "success",
            timer: 1500,
            showConfirmButton: false,
          }).then(() => {
            // Reload the DataTable
            $('.datatables-basic').DataTable().ajax.reload();
          });
        },
        error: function (xhr) {
          console.error(xhr.responseText);
          Swal.fire("Error!", "Failed to cancel the event. Please try again.", "error");
        },
      });
    }
  });
});


$(document).on('click', '.editEventBtn', function() {
    var eventId = $(this).data('id');
    
    // Reset form first
    $('#editEventForm')[0].reset();
    
    // Fetch Event Data
    $.ajax({
        url: '/organizer/events/' + eventId, // Uses your existing 'show' method
        type: 'GET',
        success: function(data) {
            // Populate fields
            $('#edit_event_id').val(data.id);
            $('#edit_title').val(data.title);
            $('#edit_client_id').val(data.client_id);
            $('#edit_description').val(data.description);
            $('#edit_venue').val(data.venue);
            $('#edit_capacity').val(data.capacity);
            
            // Format dates for datetime-local input (YYYY-MM-DDTHH:MM)
            if(data.start_date) {
                $('#edit_start_date').val(data.start_date.substring(0, 16));
            }
            if(data.end_date) {
                $('#edit_end_date').val(data.end_date.substring(0, 16));
            }
        },
        error: function() {
            Swal.fire('Error', 'Could not fetch event details', 'error');
        }
    });
});

// 2. HANDLE UPDATE SUBMISSION
$('#editEventForm').on('submit', function(e) {
    e.preventDefault();
    
    var eventId = $('#edit_event_id').val();
    var formData = new FormData(this);
    
    $.ajax({
        url: '/organizer/events/' + eventId + '/update', // Ensure this route exists!
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            $('#editEventModal').modal('hide');
            Swal.fire('Success', response.message || 'Event updated successfully!', 'success');
            
            // Reload DataTable
            if (typeof dt_basic !== "undefined") {
                dt_basic.ajax.reload(null, false);
            }
        },
        error: function(xhr) {
            var errors = xhr.responseJSON?.errors;
            var msg = 'Failed to update event.';
            if(errors) {
                msg = Object.values(errors).flat().join('\n');
            }
            Swal.fire('Error', msg, 'error');
        }
    });
});

// Delete Event
$(document).on("click", ".delete-event-btn", function () {

    let id = $(this).data("id");

    // Disabled action (completed/cancelled)
    if ($(this).hasClass("disabled")) {
        Swal.fire({
            icon: "info",
            title: "Action Not Allowed",
            text: "This event cannot be deleted.",
            confirmButtonColor: "#3085d6"
        });
        return;
    }

    Swal.fire({
        title: "Are you sure?",
        text: "This event will be permanently deleted.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it",
        cancelButtonText: "No, keep it",
        confirmButtonColor: "#d33",
        cancelButtonColor: "#6c757d"
    }).then((result) => {

        if (result.isConfirmed) {

            $.ajax({
                url: `/organizer/events/${id}/delete`,
                type: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                },
                success: function (response) {

                    Swal.fire({
                        title: "Deleted!",
                        text: response.message,
                        icon: "success",
                        timer: 1500,
                        showConfirmButton: false
                    });

                    // reload only table content (no flash)
                    if (typeof dt_basic !== "undefined") {
                        dt_basic.ajax.reload(null, false);
                    }
                },
                error: function (xhr) {
                    console.error(xhr.responseText);

                    Swal.fire({
                        title: "Error!",
                        text: "Failed to delete event.",
                        icon: "error"
                    });
                }
            });

        }
    });

});







</script>
@endsection