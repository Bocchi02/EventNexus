@extends('layouts.auth')
@section('title', 'Reset Password | EventNexus')

@section('styles')
<!-- Page -->
    <link rel="stylesheet" href="../../assets/vendor/css/pages/page-auth.css" />
@endsection

@section('content')
<div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
          <!-- Reset Password -->
          <div class="card px-sm-6 px-0">
            <div class="card-body">
              <!-- Logo -->
              <div class="app-brand justify-content-center mb-6">
                <a href="index.html" class="app-brand-link gap-2">
                  <span class="app-brand-logo demo">
                    <img src="{{ asset('assets/img/branding/eventnexus-logo.svg') }}" alt="logo" width="42">
                </span>
                  <span class="app-brand-text demo text-heading fw-bold">EventNexus</span>
                </a>
              </div>
              <!-- /Logo -->
              <h4 class="mb-1">Reset Password</h4>
              <p class="mb-6">
                <span class="fw-medium">Your new password must be different from previously used passwords</span>
              </p>
              <form id="formAuthentication" method="POST" action="{{ route('password.store') }}">
                @csrf
                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">
                <div class="mb-4">
                    <label for="email" class="form-label">Email</label>
                    <input
                    type="text"
                    class="form-control @error('email') is-invalid @enderror"
                    id="email"
                    name="email"
                    placeholder="Enter your email"
                    value="{{ old('email', $request->email) }}"
                    autofocus
                    required />
                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                    </div>
                <div class="mb-6 form-password-toggle">
                  <label class="form-label" for="password">New Password</label>
                  <div class="input-group input-group-merge">
                    <input
                      type="password"
                      id="password"
                      class="form-control"
                      name="password"
                      placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                      aria-describedby="password"
                      required
                      autocomplete="new-password"/>
                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                    @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                  </div>
                </div>
                <div class="mb-6 form-password-toggle">
                  <label class="form-label" for="password_confirmation">Confirm Password</label>
                  <div class="input-group input-group-merge">
                    <input
                      type="password"
                      id="password_confirmation"
                      class="form-control @error('password_confirmation') is-invalid @enderror"
                      name="password_confirmation"
                      placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                      aria-describedby="password"
                      required
                      autocomplete="new-password"/>
                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                     @error('password_confirmation')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                  </div>
                </div>
                <button type="submit" class="btn btn-primary d-grid w-100 mb-6">Set new password</button>
                <div class="text-center">
                  <a href="{{route('login')}}">
                    <i class="bx bx-chevron-left scaleX-n1-rtl me-1 align-top"></i>
                    Back to login
                  </a>
                </div>
              </form>
            </div>
          </div>
          <!-- /Reset Password -->
        </div>
      </div>
    </div>
@endsection

@section('scripts')
<!-- Page -->
    <script src="../../assets/js/pages/pages-auth.js"></script>
@endsection