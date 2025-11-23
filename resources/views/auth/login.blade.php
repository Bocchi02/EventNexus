@extends('layouts.auth')
@section('title', 'Login | EventNexus')

@section('styles')
<!-- Page -->
    <link rel="stylesheet" href="../../assets/vendor/css/pages/page-auth.css" />
@endsection

@section('content')

<div class="authentication-wrapper authentication-cover">
      <!-- Logo -->
      <a href="index.html" class="app-brand auth-cover-brand gap-2">
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
              src="../../assets/img/illustrations/login-illustration.png"
              class="img-fluid"
              alt="Login image"
              width="700"
              data-app-dark-img="illustrations/login-illustration.png"
              data-app-light-img="illustrations/login-illustration.png" />
          </div>
        </div>
        <!-- /Left Text -->

        <!-- Login -->
        <div class="d-flex col-12 col-lg-5 col-xl-4 align-items-center authentication-bg p-sm-12 p-6">
          <div class="w-px-400 mx-auto mt-12 pt-5">
            <h4 class="mb-1">Welcome to EventNexus!</h4>
            <p class="mb-6">Please sign-in to your account</p>

            <form id="formAuthentication" class="mb-6" method="POST" data-error="{{ $errors->first('email') }}">
                @csrf
              <div class="mb-6">
                <label for="email" class="form-label">Email</label>
                <input
                  type="email"
                  class="form-control"
                  id="email"
                  name="email"
                  placeholder="Enter your email"
                  autofocus
                  required />
              </div>
              <div class="mb-6 form-password-toggle">
                <label class="form-label" for="password">Password</label>
                <div class="input-group input-group-merge">
                  <input
                    type="password"
                    id="password"
                    class="form-control"
                    name="password"
                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                    aria-describedby="password"
                    required/>
                  <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                </div>
              </div>
              <div class="my-8">
                <div class="d-flex justify-content-between">
                  <div class="form-check mb-0 ms-2">
                    <input class="form-check-input" type="checkbox" id="remember-me" />
                    <label class="form-check-label" for="remember-me"> Remember Me </label>
                  </div>
                  <a href="auth-forgot-password-cover.html">
                    <p class="mb-0">Forgot Password?</p>
                  </a>
                </div>
              </div>
              <button class="btn btn-primary d-grid w-100">Sign in</button>
            </form>

            <p class="text-center">
              <span>New on our platform?</span>
              <a href="auth-register-cover.html">
                <span>Create an account</span>
              </a>
            </p>
          </div>
        </div>
        <!-- /Login -->
      </div>
    </div>
    @endsection

    @section('scripts')
        <script>
            window.laravelErrors = JSON.parse(`{!! json_encode([
            'email' => $errors->get('email'),
            'password' => $errors->get('password')
        ]) !!}`);

        </script>
        <script src="{{ asset('assets/js/pages-auth.js') }}"></script>

    @endsection