@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-xxl-10 mb-6 order-0">
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
    <div class="row">
     <div class="col-xxl-8 mb-6 order-2">
      <div class="card">
        <div class="table-responsive">
          <table class="table mb-0">
            <thead>
              <tr>
                <th>Project</th>
                <th>Client</th>
                <th>Users</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><i class="icon-base fab fa-angular text-danger me-4"></i> <span class="fw-medium">Angular Project</span></td>
                <td>Albert Cook</td>
                <td>
                  <ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center">
                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-xs pull-up" title="Lilian Fuller">
                      <img src="../../assets/img/avatars/5.png" alt="Avatar" class="rounded-circle">
                    </li>
                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-xs pull-up" title="Sophia Wilkerson">
                      <img src="../../assets/img/avatars/6.png" alt="Avatar" class="rounded-circle">
                    </li>
                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-xs pull-up" title="Christina Parker">
                      <img src="../../assets/img/avatars/7.png" alt="Avatar" class="rounded-circle">
                    </li>
                  </ul>
                </td>
                <td><span class="badge bg-label-primary me-1">Active</span></td>
                <td>
                  <div class="dropdown">
                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="icon-base bx bx-dots-vertical-rounded"></i></button>
                    <div class="dropdown-menu">
                      <a class="dropdown-item" href="javascript:void(0);"><i class="icon-base bx bx-edit-alt me-1"></i>Edit</a>
                      <a class="dropdown-item" href="javascript:void(0);"><i class="icon-base bx bx-trash me-1"></i>Delete</a>
                    </div>
                  </div>
                </td>
              </tr>
              <tr>
                <td><i class="icon-base fab fa-react text-info me-4"></i> <span class="fw-medium">React Project</span></td>
                <td>Barry Hunter</td>
                <td>
                  <ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center">
                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-xs pull-up" title="Lilian Fuller">
                      <img src="../../assets/img/avatars/5.png" alt="Avatar" class="rounded-circle">
                    </li>
                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-xs pull-up" title="Sophia Wilkerson">
                      <img src="../../assets/img/avatars/6.png" alt="Avatar" class="rounded-circle">
                    </li>
                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-xs pull-up" title="Christina Parker">
                      <img src="../../assets/img/avatars/7.png" alt="Avatar" class="rounded-circle">
                    </li>
                  </ul>
                </td>
                <td><span class="badge bg-label-success me-1">Completed</span></td>
                <td>
                  <div class="dropdown">
                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="icon-base bx bx-dots-vertical-rounded"></i></button>
                    <div class="dropdown-menu">
                      <a class="dropdown-item" href="javascript:void(0);"><i class="icon-base bx bx-edit-alt me-2"></i>Edit</a>
                      <a class="dropdown-item" href="javascript:void(0);"><i class="icon-base bx bx-trash me-2"></i>Delete</a>
                    </div>
                  </div>
                </td>
              </tr>
              <tr>
                <td><i class="icon-base fab fa-vuejs text-success me-4"></i> <span class="fw-medium">VueJs Project</span></td>
                <td>Trevor Baker</td>
                <td>
                  <ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center">
                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-xs pull-up" title="Lilian Fuller">
                      <img src="../../assets/img/avatars/5.png" alt="Avatar" class="rounded-circle">
                    </li>
                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-xs pull-up" title="Sophia Wilkerson">
                      <img src="../../assets/img/avatars/6.png" alt="Avatar" class="rounded-circle">
                    </li>
                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-xs pull-up" title="Christina Parker">
                      <img src="../../assets/img/avatars/7.png" alt="Avatar" class="rounded-circle">
                    </li>
                  </ul>
                </td>
                <td><span class="badge bg-label-info me-1">Scheduled</span></td>
                <td>
                  <div class="dropdown">
                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="icon-base bx bx-dots-vertical-rounded"></i></button>
                    <div class="dropdown-menu">
                      <a class="dropdown-item" href="javascript:void(0);"><i class="icon-base bx bx-edit-alt me-2"></i>Edit</a>
                      <a class="dropdown-item" href="javascript:void(0);"><i class="icon-base bx bx-trash me-2"></i>Delete</a>
                    </div>
                  </div>
                </td>
              </tr>
              <tr>
                <td><i class="icon-base fab fa-bootstrap text-primary me-4"></i> <span class="fw-medium">Bootstrap Project</span></td>
                <td>Jerry Milton</td>
                <td>
                  <ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center">
                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-xs pull-up" title="Lilian Fuller">
                      <img src="../../assets/img/avatars/5.png" alt="Avatar" class="rounded-circle">
                    </li>
                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-xs pull-up" title="Sophia Wilkerson">
                      <img src="../../assets/img/avatars/6.png" alt="Avatar" class="rounded-circle">
                    </li>
                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-xs pull-up" title="Christina Parker">
                      <img src="../../assets/img/avatars/7.png" alt="Avatar" class="rounded-circle">
                    </li>
                  </ul>
                </td>
                <td><span class="badge bg-label-warning me-1">Pending</span></td>
                <td>
                  <div class="dropdown">
                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="icon-base bx bx-dots-vertical-rounded"></i></button>
                    <div class="dropdown-menu">
                      <a class="dropdown-item" href="javascript:void(0);"><i class="icon-base bx bx-edit-alt me-2"></i>Edit</a>
                      <a class="dropdown-item" href="javascript:void(0);"><i class="icon-base bx bx-trash me-2"></i>Delete</a>
                    </div>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
     </div>
    </div>
</div>
@endsection

@section('scripts')
    <!-- Vendors JS -->
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>
@endsection