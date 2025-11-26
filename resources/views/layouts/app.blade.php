<!DOCTYPE html>
<html
  lang="{{ str_replace('_', '-', app()->getLocale()) }}"
  class="light-style layout-wide customizer-hide"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="{{ asset('assets/') . '/' }}"
  data-style="light"
>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title',config('app.name'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap"
      rel="stylesheet"
    />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/fontawesome.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/flag-icons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/typeahead-js/typeahead.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css')}}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css')}}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css')}}" />

    @yield('styles')

    <!--select2 cdn -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <!-- <script src="{{ asset('assets/vendor/js/template-customizer.js') }}"></script> -->
    <script src="{{ asset('assets/js/config.js') }}"></script>
    <!-- Sweetalert cdn -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
  </head>

  <body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">@include('partials.sidebar')</aside>
            <div class="layout-page">
                @include('partials.navbar')

                <div class="content-wrapper">
                  @yield('content')
                </div>
            </div>
        </div>  
    </div>

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

      <!-- User Avatar -->
       <script>
              // Random index (0–6)
              var stateNum = Math.floor(Math.random() * 7);

              // Available color classes
              var states = [
                  "bg-success",
                  "bg-danger",
                  "bg-warning",
                  "bg-info",
                  "bg-dark",
                  "bg-primary",
                  "bg-secondary",
              ];

              // Select multiple avatar IDs
              var avatars = document.querySelectorAll('#userAvatar, #userAvatar2');

              // Loop through and apply color
              avatars.forEach(function(avatar) {
                  avatar.classList.add(states[stateNum]);
              });
          </script>


      <!-- Page JS -->
    @yield('scripts')
  </body>
</html>
