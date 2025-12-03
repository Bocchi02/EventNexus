@extends('layouts.auth')

@section('title', 'Forgot Password | EventNexus')

@section('styles')
<!-- Page -->
    <link rel="stylesheet" href="../../assets/vendor/css/pages/page-auth.css" />
@endsection

@section('content')
<div class="authentication-wrapper authentication-cover">
      <!-- Logo -->
      <a href="#" class="app-brand auth-cover-brand gap-2">
        <span class="app-brand-logo demo">
          <img src="{{ asset('assets/img/branding/eventnexus-logo.svg') }}" alt="logo" width="42">
        </span>
        <span class="app-brand-text demo text-heading fw-bold">EventNexus</span>
      </a>
      <!-- /Logo -->
      <div class="authentication-inner row m-0">
        <!-- /Left Text -->
        <div class="d-none d-lg-flex col-lg-7 col-xl-8 align-items-center p-5">
          <div class="w-100 d-flex justify-content-center">
            <img
              src="../../assets/img/illustrations/girl-unlock-password-light.png"
              class="img-fluid scaleX-n1-rtl"
              alt="Login image"
              width="700"
              data-app-dark-img="illustrations/girl-unlock-password-dark.png"
              data-app-light-img="illustrations/girl-unlock-password-light.png" />
          </div>
        </div>
        <!-- /Left Text -->
        <!-- Forgot Password -->
        <div class="d-flex col-12 col-lg-5 col-xl-4 align-items-center authentication-bg p-sm-12 p-6">
          <div class="w-px-400 mx-auto mt-12 mt-5">
            <h4 class="mb-1">Forgot Password?</h4>
            <p class="mb-6">No problem! Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.</p>
             @if (session('status'))
                <div class="mb-4">
                    <div class="alert alert-success py-2 px-3 w-100">
                        {{ session('status') }}
                    </div>
                </div>
            @endif
            <form id="formAuthentication" class="mb-6" action="{{ route('password.email') }}" method="POST">
                @csrf
              <div class="mb-6">
                <label for="email" class="form-label">Email</label>
                <input
                  type="text"
                  class="form-control @error('email') is-invalid @enderror"
                  id="email"
                  name="email"
                  placeholder="Enter your email"
                  value="{{ old('email') }}"
                  autofocus
                  required />
                  @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
              </div>
              <button type="submit" class="btn btn-primary d-grid w-100">Send Reset Link</button>
            </form>
            <div class="text-center">
              <a href="{{route('login')}}" class="d-flex align-items-center justify-content-center">
                <i class="bx bx-chevron-left scaleX-n1-rtl me-1_5 align-top"></i>
                Back to login
              </a>
            </div>
          </div>
        </div>
        <!-- /Forgot Password -->
      </div>
    </div>
@endsection

@section('scripts')
  <script src="{{ asset('assets/js/pages-auth.js') }}"></script>
@endsection