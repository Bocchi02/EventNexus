@extends('layouts.auth')
@section('title', 'Register | EventNexus')

@section('styles')
<!-- Page -->
    <link rel="stylesheet" href="../../assets/vendor/css/pages/page-auth.css" />
@endsection

@section('content')
<div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
          <!-- Register Card -->
          <div class="card px-sm-6 px-0">
            <div class="card-body">
              <!-- Logo -->
              <div class="app-brand justify-content-center mb-6">
                <a href="index.html" class="app-brand-link gap-2">
                  <span class="app-brand-logo demo">
                    <img src="{{ asset('assets/img/branding/eventnexus-logo.svg') }}" alt="logo" width="35">
                  </span>
                  <span class="app-brand-text demo text-heading fw-bold">EventNexus</span>
                </a>
              </div>
              <!-- /Logo -->
              <h4 class="mb-1">Your events start here</h4>
              <p class="mb-6">Create, manage, and monitor events with ease.</p>
              <form id="formAuthentication" class="mb-6" method="POST" action="{{ route('register') }}">
                  @csrf

                  <!-- ROW 1: Personal Details -->
                  <div class="row">
                      <div class="col-md-4 mb-4">
                          <label for="firstname" class="form-label">First Name</label>
                          <input type="text" id="firstname" name="firstname" class="form-control" placeholder="Enter first name" required>
                      </div>

                      <div class="col-md-4 mb-4">
                          <label for="middlename" class="form-label">Middle Name</label>
                          <input type="text" id="middlename" name="middlename" class="form-control" placeholder="(Optional)">
                      </div>

                      <div class="col-md-4 mb-4">
                          <label for="lastname" class="form-label">Last Name</label>
                          <input type="text" id="lastname" name="lastname" class="form-control" placeholder="Enter last name" required>
                      </div>
                  </div>

                  <!-- ROW 2: Roles -->
                  <div class="row">
                      <label class="form-label d-block mb-2">Select Your Role</label>

                      <!-- Organizer -->
                      <div class="col-md-4 mb-4">
                          <div class="form-check custom-option custom-option-icon">
                              <label class="form-check-label custom-option-content" for="role-organizer">
                                  <span class="custom-option-body">
                                      <i class='bx bx-calendar-edit'></i>
                                      <span class="custom-option-title">Organizer</span>
                                      <small>Organizers can create events, edit or cancel them, and assign each event to the appropriate clients.</small>
                                  </span>
                                  <input name="role" class="form-check-input" type="radio" value="organizer" id="role-organizer" checked />
                              </label>
                          </div>
                      </div>

                      <!-- Client -->
                      <div class="col-md-4 mb-4">
                          <div class="form-check custom-option custom-option-icon">
                              <label class="form-check-label custom-option-content" for="role-client">
                                  <span class="custom-option-body">
                                      <i class='bx bx-id-card'></i>
                                      <span class="custom-option-title">Client</span>
                                      <small>Clients can manage their assigned events, invite guests to participate, and view attendee list.</small>
                                  </span>
                                  <input name="role" class="form-check-input" type="radio" value="client" id="role-client" />
                              </label>
                          </div>
                      </div>

                      <!-- Guest -->
                      <div class="col-md-4 mb-4">
                          <div class="form-check custom-option custom-option-icon">
                              <label class="form-check-label custom-option-content" for="role-guest">
                                  <span class="custom-option-body">
                                      <i class='bx bx-user-pin'></i>
                                      <span class="custom-option-title">Guest</span>
                                      <small>Guests can view their invited events, accept invitations, or cancel them whenever necessary.</small>
                                  </span>
                                  <input name="role" class="form-check-input" type="radio" value="guest" id="role-guest" />
                              </label>
                          </div>
                      </div>
                  </div>

                  <!-- ROW 3: Email -->
                  <div class="row">
                      <div class="col-md-12 mb-4">
                          <label for="email" class="form-label">Email</label>
                          <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required>
                      </div>
                  </div>

                  <!-- ROW 4: Password -->
                  <div class="row">
                      <div class="col-md-12 mb-4 form-password-toggle">
                          <label class="form-label" for="password">Password</label>
                          <div class="input-group input-group-merge">
                              <input
                                  type="password"
                                  id="password"
                                  class="form-control"
                                  name="password"
                                  placeholder="••••••••••••"
                                  required>
                              <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                          </div>
                      </div>

                      <div class="col-md-12 mb-4 form-password-toggle">
                          <label class="form-label" for="password_confirmation">Confirm Password</label>
                          <div class="input-group input-group-merge">
                              <input
                                  type="password"
                                  id="password_confirmation"
                                  class="form-control"
                                  name="password_confirmation"
                                  placeholder="••••••••••••"
                                  required>
                              <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                          </div>
                      </div>
                  </div>

                  <!-- Submit -->
                  <button class="btn btn-primary d-grid w-100 mt-2">Sign up</button>
              </form>

              <p class="text-center">
                <span>Already have an account?</span>
                <a href="{{route('login')}}">
                  <span>Sign in instead</span>
                </a>
              </p>

            </div>
          </div>
          <!-- Register Card -->
        </div>
      </div>
    </div>
@endsection

@section('scripts')
<script>
      // Check selected custom option
      window.Helpers.initCustomOptionCheck();
</script>
<script src="{{asset('assets/js/pages-auth.js')}}"></script>
@endsection