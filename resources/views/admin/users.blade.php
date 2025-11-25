@extends('layouts.app')
@section('title', 'User Management | EventNexus')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- DataTable with Buttons -->
              <div class="card">
                <div class="card-datatable table-responsive">
                  <table class="datatables-basic table border-top">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Date Added</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                  </table>
                </div>
              </div>
              <!-- Modal to add new record -->
              <div class="modal fade" id="addNewUserModal" tabindex="-1" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="modalCenterTitle">Add New User</h5>
                                <button
                                  type="button"
                                  class="btn-close"
                                  data-bs-dismiss="modal"
                                  aria-label="Close"></button>
                              </div>
                              <div class="modal-body">
                                <form id="addNewUserForm">
                                  @csrf
                                  <div class="row">
                                  <div class="col mb-3">
                                    <label for="firstname" class="form-label">First Name</label>
                                    <input
                                      type="text"
                                      id="firstname"
                                      name="firstname"
                                      class="form-control"
                                      placeholder="John" />
                                  </div>
                                  <div class="col mb-6">
                                    <label for="middlename" class="form-label">Middle Name<small>(Optional)</small></label>
                                    <input
                                      type="text"
                                      id="middlename"
                                      name="middlename"
                                      class="form-control"
                                      placeholder="(Optional)" optional/>
                                  </div>
                                  <div class="col mb-6">
                                    <label for="lastname" class="form-label">Last Name</label>
                                    <input
                                      type="text"
                                      id="lastname"
                                      name="lastname"
                                      class="form-control"
                                      placeholder="Doe" />
                                  </div>
                                </div>
                                <div class="row">
                                  <div class="col mb-6">
                                    <label for="roles" class="form-label">Roles</label>
                                  <select name="role" class="form-select">
                                    <option selected>-- Select Role --</option>
                                    @foreach($roles as $role)
                                      <option value="{{$role->name}}">{{ucfirst($role->name)}}</option>
                                    @endforeach
                                  </select>
                                  </div>
                                </div>
                                <div class="row">
                                  <div class="col mb-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input
                                      type="email"
                                      id="email"
                                      class="form-control"
                                      name="email"
                                      placeholder="john.doe@example.com" />
                                  </div>
                                </div>
                                <div class="row">
                                  <div class="col mb-0">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" name="password" id="dobWithTitle" class="form-control" />
                                  </div>
                                  <div class="col mb-0">
                                    <label for="confirmPassword" class="form-label">Confirm Password</label>
                                    <input type="password" name="password_confirmation" id="dobWithTitle" class="form-control" />
                                  </div>
                                </div>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                                  Close
                                </button>
                                <button type="button" id="addUserBtn" class="btn btn-primary">Add User</button>
                              </div>
                                </form> 
                            </div>
                          </div>
                        </div>

                        <!-- Modal to edit user -->
              <div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="modalCenterTitle">Edit User</h5>
                                <button
                                  type="button"
                                  class="btn-close"
                                  data-bs-dismiss="modal"
                                  aria-label="Close"></button>
                              </div>
                              <div class="modal-body">
                                <form id="editUserForm">
                                  @csrf
                                  <div class="row">
                                  <div class="col mb-3">
                                    <label for="firstname" class="form-label">First Name</label>
                                    <input
                                      type="text"
                                      id="firstname"
                                      name="firstname"
                                      class="form-control"/>
                                  </div>
                                  <div class="col mb-6">
                                    <label for="middlename" class="form-label">Middle Name<small>(Optional)</small></label>
                                    <input
                                      type="text"
                                      id="middlename"
                                      name="middlename"
                                      class="form-control"
                                      placeholder="(Optional)" optional/>
                                  </div>
                                  <div class="col mb-6">
                                    <label for="lastname" class="form-label">Last Name</label>
                                    <input
                                      type="text"
                                      id="lastname"
                                      name="lastname"
                                      class="form-control"
                                      placeholder="Doe" />
                                  </div>
                                </div>
                                <div class="row">
                                  <div class="col mb-6">
                                    <label for="roles" class="form-label">Roles</label>
                                  <select name="role" class="form-select">
                                    <option selected>-- Select Role --</option>
                                    @foreach($roles as $role)
                                      <option value="{{$role->name}}">{{ucfirst($role->name)}}</option>
                                    @endforeach
                                  </select>
                                  </div>
                                </div>
                                <div class="row">
                                  <div class="col mb-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input
                                      type="email"
                                      id="email"
                                      class="form-control"
                                      name="email"
                                      placeholder="john.doe@example.com" />
                                  </div>
                                </div>
                                <div class="row">
                                  <div class="col mb-0">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" name="password" id="dobWithTitle" class="form-control" />
                                  </div>
                                  <div class="col mb-0">
                                    <label for="confirmPassword" class="form-label">Confirm Password</label>
                                    <input type="password" name="password_confirmation" id="dobWithTitle" class="form-control" />
                                  </div>
                                </div>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                                  Close
                                </button>
                                <button type="button" id="saveEditUserBtn" class="btn btn-primary">Save changes</button>
                              </div>
                                </form> 
                            </div>
                          </div>
                        </div>
                        
              <!--/ DataTable with Buttons -->
</div>
@endsection

@section('scripts')
 

      <script>
        var dt_basic_table = $(".datatables-basic"),dt_basic;

        // DataTable with buttons
        // --------------------------------------------------------------------
        if (dt_basic_table.length) {
            dt_basic = dt_basic_table.DataTable({
                ajax: "/admin/getUsers",
                columns: [
                    { data: null }, // 0. For Responsive control
                    { data: null }, // 1. For Name/Avatar
                    { data: "email" }, // 2.
                    { data: "status" }, // 3.
                    { data: "created_at" }, // 4.
                    { data: "" }, // 5.
                ],
                columnDefs: [
                    {
                        // 0. For Responsive
                        className: "control",
                        orderable: false,
                        searchable: false,
                        responsivePriority: 1,
                        targets: 0,
                        render: function () {
                            return "";
                        },
                    },
                    {
                        // 1. Avatar image/badge, Name and post
                        targets: 1,
                        responsivePriority: 2,
                        render: function (data, type, full, meta) {
                            var $user_img = full["avatar"],
                                $name = `${full.firstname} ${
                                    full.middlename ? full.middlename + " " : ""
                                }${full.lastname}`,
                                // Capitalize first letter of role
                                $post = full["role"]
                                    ? full["role"].charAt(0).toUpperCase() +
                                      full["role"].slice(1)
                                    : "";

                            if ($user_img) {
                                // For Avatar image
                                var $output =
                                    '<img src="' +
                                    assetsPath +
                                    "img/avatars/" +
                                    $user_img +
                                    '" alt="Avatar" class="rounded-circle">';
                            } else {
                                // For Avatar badge
                                var stateNum = Math.floor(Math.random() * 6);
                                var states = [
                                    "success",
                                    "danger",
                                    "warning",
                                    "info",
                                    "dark",
                                    "primary",
                                    "secondary",
                                ];
                                var $state = states[stateNum],
                                    $initials = $name.match(/\b\w/g) || [];
                                $initials = (
                                    ($initials.shift() || "") + ($initials.pop() || "")
                                ).toUpperCase();
                                $output =
                                    '<span class="avatar-initial rounded-circle bg-label-' +
                                    $state +
                                    '">' +
                                    $initials +
                                    "</span>";
                            }
                            // Creates full output for row
                            var $row_output =
                                '<div class="d-flex justify-content-start align-items-center user-name">' +
                                '<div class="avatar-wrapper">' +
                                '<div class="avatar me-2">' +
                                $output +
                                "</div>" +
                                "</div>" +
                                '<div class="d-flex flex-column">' +
                                '<span class="emp_name text-truncate">' +
                                $name +
                                "</span>" +
                                '<small class="emp_post text-truncate text-muted">' +
                                $post +
                                "</small>" +
                                "</div>" +
                                "</div>";
                            return $row_output;
                        },
                    },
                    {
                        // 3. Label (Status) - UPDATED from 4 to 3
                        targets: 3,
                        render: function (data, type, full, meta) {
                            var $status_name = full["status"];
                            var $status = {
                                active: {
                                    title: "Active",
                                    class: "bg-label-success",
                                },
                                inactive: {
                                    title: "Inactive",
                                    class: "bg-label-danger",
                                },
                                pending: {
                                    title: "Pending",
                                    class: "bg-label-warning",
                                },
                            };
                            if (typeof $status[$status_name] === "undefined") {
                                return data;
                            }
                            return (
                                '<span class="badge ' +
                                $status[$status_name].class +
                                '">' +
                                $status[$status_name].title +
                                "</span>"
                            );
                        },
                    },
                    {
                        // 4. Created At (Date) - UPDATED from 5 to 4
                        targets: 4,
                        render: function (data, type, full, meta) {
                            if (!data) return "";
                            var date = new Date(data);
                            return date.toLocaleDateString("en-US", {
                                year: "numeric",
                                month: "short",
                                day: "numeric",
                            });
                        },
                    },
                    {
                        // 5. Actions (Last column) - 'targets: -1' still works
                        targets: -1,
                        title: "Actions",
                        orderable: false,
                        searchable: false,
                        responsivePriority: 1,
                        render: function (data, type, full, meta) {
                            let deleteUrl = `{{ route('admin.deleteUser', ['id' => ':id']) }}`;
                            deleteUrl = deleteUrl.replace(":id", full.id);
                            return (
                                '<div class="d-inline-block">' +
                                '<a href="javascript:;" class="btn btn-icon dropdown-toggle hide-arrow me-1" data-bs-toggle="dropdown">' +
                                '<i class="bx bx-dots-vertical-rounded bx-md"></i></a>' +
                                '<ul class="dropdown-menu dropdown-menu-end m-0">' +
                                `<li><a href="javascript:;" class="dropdown-item toggle-status" data-id="${full.id}" data-status="${full.status}">${full.status === 'active' ? 'Deactivate' : 'Activate'}</a></li>` +
                                '<div class="dropdown-divider"></div>' +
                                `<li><a href="${deleteUrl}" class="dropdown-item text-danger delete-record">Delete</a></li>` +
                                "</ul></div>" +
                                '<a href="javascript:;" class="btn btn-icon item-edit editUserBtn" data-bs-toggle="modal" data-bs-target="#editUserModal"><i class="bx bx-edit bx-md"></i></a>'
                            );
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
                        text: '<i class="bx bx-plus bx-sm me-sm-2"></i> <span class="d-none d-sm-inline-block">Add New User</span>',
                        className: "create-new btn btn-primary",
                        attr: {
                            type: "button",
                            "data-bs-toggle": "modal",
                            "data-bs-target": "#addNewUserModal",
                        },
                    },
                ],
                responsive: {
                    details: {
                        display: $.fn.dataTable.Responsive.display.modal({
                            header: function (row) {
                                var data = row.data();
                                return "Details of " + data["full_name"];
                            },
                        }),
                        type: "column",
                        renderer: function (api, rowIdx, columns) {
                            var data = $.map(columns, function (col, i) {
                                return col.title !== "" // ? Do not show row in modal popup if title is blank (for check box)
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
            $("div.head-label").html('<h5 class="card-title mb-0">All Users</h5>');
            // To remove default btn-secondary in export buttons
            $(".dt-buttons > .btn-group > button").removeClass("btn-secondary");
        }

        $("#addUserBtn").on("click", function (e) {
          e.preventDefault();
          var formData = new FormData($("#addNewUserForm")[0]);

          $.ajax({
              url: "{{ route('admin.storeUser') }}",
              type: "POST",
              data: formData,
              processData: false,
              contentType: false,
              success: function (response) {
                  $("#addNewUserModal").modal("hide");
                  Swal.fire({
                      title: response.title,
                      text: response.success,
                      icon: "success",
                      timer: 1000,
                      showConfirmButton: false,
                  }).then(() => {
                      dt_basic_table.DataTable().ajax.reload();
                  });
              },
              error: function (xhr) {
                  var errors = xhr.responseJSON.errors;
                  var errorMessages = "";
                  $.each(errors, function (key, value) {
                      errorMessages += value[0] + "\n";
                  });
                  $("#addNewUserModal").modal("hide");
                  Swal.fire({
                      title: "Error!",
                      text: errorMessages,
                      icon: "error",
                      confirmButtonText: "OK",
                  });
              },
          });
      });
        

        // Open Edit Modal and populate fields
$(document).on("click", ".editUserBtn", function () {
    const rowData = dt_basic_table.DataTable().row($(this).closest("tr")).data();

    $("#editUserForm input[name='firstname']").val(rowData.firstname);
    $("#editUserForm input[name='middlename']").val(rowData.middlename);
    $("#editUserForm input[name='lastname']").val(rowData.lastname);
    $("#editUserForm input[name='email']").val(rowData.email);

    $("#editUserForm").attr("data-id", rowData.id);

    $("#editUserModal").modal("show");

    // Delay to ensure modal DOM is ready
    setTimeout(() => {
        $("#editUserForm select[name='role']").val(rowData.role_name || rowData.role).change();
    }, 100);
});


// Save Changes
$(document).on("click", "#saveEditUserBtn", function (e) {
    e.preventDefault();
    const id = $("#editUserForm").attr("data-id");

    const formData = new FormData($("#editUserForm")[0]);

    // If password fields are empty, remove them to retain old password
    if (!formData.get("password")) formData.delete("password");
    if (!formData.get("password_confirmation")) formData.delete("password_confirmation");

    $.ajax({
        url: `/admin/users/edit/${id}`,
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            $("#editUserModal").modal("hide");
            Swal.fire({
                title: response.title,
                text: response.success,
                icon: "success",
                timer: 1000,
                showConfirmButton: false,
            }).then(() => {
                dt_basic_table.DataTable().ajax.reload();
            });
        },
        error: function (xhr) {
            const errors = xhr.responseJSON?.errors || {};
            let errorMessages = "";
            $.each(errors, function (key, value) {
                errorMessages += value[0] + "\n";
            });
            Swal.fire({
                title: "Error!",
                text: errorMessages || "An unknown error occurred.",
                icon: "error",
                confirmButtonText: "OK",
            });
        },
    });
});

// Toggle User Status
$(document).on("click", ".toggle-status", function () {
    const id = $(this).data("id");
    const currentStatus = $(this).data("status");

    $.ajax({
        url: `/admin/users/toggle-status/${id}`,
        type: "POST",
        data: { _token: $('meta[name="csrf-token"]').attr("content") },
        success: function (response) {
            Swal.fire({
                title: "Success",
                text: `User has been ${response.status === 'active' ? 'activated' : 'deactivated'}.`,
                icon: "success",
                timer: 1000,
                showConfirmButton: false,
            });
            dt_basic_table.DataTable().ajax.reload();
        },
        error: function () {
            Swal.fire("Error", "Failed to update user status.", "error");
        },
    });
});


      </script>

@endsection