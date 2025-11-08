@extends('layouts.app')
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
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" id="addEventBtn" class="btn btn-primary">Create Event</button>
                    </div>
                </form>
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
                type: "GET",
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
                                <small class="text-muted">${full.client}</small>
                            </div>`;
                    },
                },
                {
                    targets: 3,
                    render: function (data) {
                        if (!data) return "";
                        const d = new Date(data);
                        return d.toLocaleDateString("en-US", {
                            year: "numeric",
                            month: "short",
                            day: "numeric",
                        });
                    },
                },
                {
                    targets: 4,
                    render: function (data) {
                        if (!data) return "";
                        const d = new Date(data);
                        return d.toLocaleDateString("en-US", {
                            year: "numeric",
                            month: "short",
                            day: "numeric",
                        });
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
                        const viewUrl = `/organizer/events/${full.id}`;
                        const deleteUrl = `/organizer/events/${full.id}`;
                        return `
                            <div class="d-inline-block">
                                <a href="javascript:;" class="btn btn-icon dropdown-toggle hide-arrow me-1" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded bx-md"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end m-0">
                                    <li><a href="${viewUrl}" class="dropdown-item">View</a></li>
                                    <div class="dropdown-divider"></div>
                                    <li><a href="${deleteUrl}" class="dropdown-item text-danger delete-record">Delete</a></li>
                                </ul>
                            </div>
                            <a href="javascript:;" class="btn btn-icon item-edit editEventBtn" data-id="${full.id}" data-bs-toggle="modal" data-bs-target="#editEventModal">
                                <i class="bx bx-edit bx-md"></i>
                            </a>`;
                    },
                },
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
                if (typeof dt_basic !== "undefined") {
                    dt_basic.ajax.reload();
                }
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



</script>
@endsection