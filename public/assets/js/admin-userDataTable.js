var dt_basic_table = $(".datatables-basic");

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
                        '<a href="javascript:;" class="btn btn-icon dropdown-toggle hide-arrow me-1" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded bx-md"></i></a>' +
                        '<ul class="dropdown-menu dropdown-menu-end m-0">' +
                        '<li><a href="javascript:;" class="dropdown-item">Details</a></li>' +
                        '<li><a href="javascript:;" class="dropdown-item">Archive</a></li>' +
                        '<div class="dropdown-divider"></div>' +
                        `<li><a href="${deleteUrl}" class="dropdown-item text-danger delete-record">Delete</a></li>` +
                        "</ul>" +
                        "</div>" +
                        '<a href="javascript:;" class="btn btn-icon item-edit" id="editUserBtn" data-bs-toggle="modal" data-bs-target="#editUserModal"><i class="bx bx-edit bx-md"></i></a>'
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
                text: '<i class="bx bx-plus bx-sm me-sm-2"></i> <span class="d-none d-sm-inline-block">Add New Record</span>',
                className: "create-new btn btn-primary",
                attr: {
                    type: "button",
                    "data-bs-toggle": "modal",
                    "data-bs-target": "#addNewRecordModal",
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
            $("#addNewRecordModal").modal("hide");
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
            Swal.fire({
                title: "Error!",
                text: errorMessages,
                icon: "error",
                confirmButtonText: "OK",
            });
        },
    });
});

$(document).on("click", "#editUserBtn", function () {
    var rowData = dt_basic_table.DataTable().row($(this).closest("tr")).data();
    $("#editUserForm input[name='firstname']").val(rowData.firstname);
    $("#editUserForm input[name='middlename']").val(rowData.middlename);
    $("#editUserForm input[name='lastname']").val(rowData.lastname);
    $("#editUserForm input[name='email']").val(rowData.email);
    // populate other fields

    $("#editUserModal").modal("show");
});

//Edit User
$("#editUserBtn").on("click", function (e) {
    e.preventDefault();
    var rowData = dt_basic_table.DataTable().row($(this).closest("tr")).data();
    $("#editUserForm input[name='firstname']").val(rowData.firstname);
    $.ajax({
        url: "{{ route('admin.editUser') }}",
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
            var errors = xhr.responseJSON.errors;
            var errorMessages = "";
            $.each(errors, function (key, value) {
                errorMessages += value[0] + "\n";
            });
            Swal.fire({
                title: "Error!",
                text: errorMessages,
                icon: "error",
                confirmButtonText: "OK",
            });
        },
    });
});
