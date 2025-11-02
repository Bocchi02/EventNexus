@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-xxl-10 mb-6  order-0">
            <div class="card">
                    <div class="d-flex row">
                      <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 ps-10 text-sm-start text-center">
                          <img src="../../assets/img/illustrations/man-with-laptop.png" height="181" alt="Target User">
                        </div>
                      </div>
                      <div class="col-sm-7">
                        <div class="card-body">
                          <h5 class="card-title text-primary mb-3">Welcome back {{Auth::user()->firstname}}!</h5>
                          <p class="mb-6">
                            You have 12 task to finish today, Your already completed 189 task good job.
                          </p>
                          <span class="badge bg-label-primary">78% of TARGET</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-2 mb-6 order-1">
          <div class="card h-100">
          <div class="card-body">
            <div class="card-title d-flex align-items-start justify-content-between mb-4">
              <div class="avatar flex-shrink-0">
                <span class="badge bg-label-success rounded p-2">
                            <i class="bx bx-user bx-lg"></i>
                          </span>
              </div>
              <div class="dropdown">
                <button class="btn p-0" type="button" id="cardOpt6" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="icon-base bx bx-dots-vertical-rounded text-body-secondary"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt6">
                  <a class="dropdown-item" href="{{route('admin.users')}}">View More</a>
                </div>
              </div>
            </div>
            <p class="mb-1">Total Users</p>
            <h4 class="card-title mb-3">{{ $totalUsers }}</h4>

            @if($growth > 0)
                <small class="text-success fw-medium">
                    <i class="icon-base bx bx-up-arrow-alt"></i> +{{ number_format($growth, 2) }}%
                </small>
            @elseif($growth < 0)
                <small class="text-danger fw-medium">
                    <i class="icon-base bx bx-down-arrow-alt"></i> {{ number_format($growth, 2) }}%
                </small>
            @else
                <small class="text-muted fw-medium">
                    <i class="icon-base bx bx-minus"></i> 0%
                </small>
            @endif
          </div>
        </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
  <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->

    <script src="{{ asset('/assets/vendor/libs/jquery/jquery.js')}}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js')}}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js')}}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>
    <script src="{{ asset('assets/vendor/libs/hammer/hammer.js')}}"></script>
    <script src="{{ asset('assets/vendor/libs/i18n/i18n.js')}}"></script>
    <script src="{{ asset('assets/vendor/libs/typeahead-js/typeahead.js')}}"></script>
    <script src="{{ asset('assets/vendor/js/menu.js')}}"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>

    <!-- Main JS -->
    <script src="{{ asset('assets/js/main.js')}}"></script>

@endsection