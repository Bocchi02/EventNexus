@extends('layouts.app')

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
              <div class="modal fade" id="addNewRecordModal" tabindex="-1" aria-hidden="true">
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
                                <button type="button" id="addUserBtn" class="btn btn-primary">Save changes</button>
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
                                <button type="button" id="addUserBtn" class="btn btn-primary">Save changes</button>
                              </div>
                                </form> 
                            </div>
                          </div>
                        </div>
                        
              <!--/ DataTable with Buttons -->
</div>
@endsection

@section('scripts')
  <!-- Core JS -->
      <!-- build:js assets/vendor/js/core.js -->

      <script src="{{ asset('assets/vendor/libs/jquery/jquery.js')}}"></script>
      <script src="{{ asset('assets/vendor/libs/popper/popper.js')}}"></script>
      <script src="{{ asset('assets/vendor/js/bootstrap.js')}}"></script>
      <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>
      <script src="{{ asset('assets/vendor/libs/hammer/hammer.js')}}"></script>
      <script src="{{ asset('assets/vendor/libs/i18n/i18n.js')}}"></script>
      <script src="{{ asset('assets/vendor/libs/typeahead-js/typeahead.js')}}"></script>
      <script src="{{ asset('assets/vendor/js/menu.js')}}"></script>
      
      <!-- Vendors JS -->
      <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>
      <!-- Flat Picker -->
      <script src="{{ asset('assets/vendor/libs/moment/moment.js')}}"></script>
      <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js')}}"></script>

      <!-- Main JS -->
      <script src="{{ asset('assets/js/main.js')}}"></script>

      <script src="{{ asset('assets/js/admin-userDataTable.js')}}"></script>

@endsection