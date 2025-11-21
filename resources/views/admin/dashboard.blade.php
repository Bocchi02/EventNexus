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
      <div class="col-xxl-6 mb-6 order-2">
        <div class="card">
          <div class="table-responsive">
            <table class="table mb-0">  
              <thead>
                <tr>
                  <th>Upcoming Events</th>
                  <th>Organizer</th>
                  <th>Client</th>
                  <th>Status</th>
                </tr>
              </thead>

              <tbody>
                @forelse ($upcomingEvents as $upcomingEvent)
                <tr>
                  <td>
                    <i class="icon-base bx bxs-calendar-event text-primary me-1 "></i>
                    <span class="fw-medium">{{ $upcomingEvent->title }}</span>
                    <br>
                    <small class="text-muted">
                      {{ \Carbon\Carbon::parse($upcomingEvent->start_date)->format('M d, Y h:i A') }}
                    </small>
                  </td>

                  <td>{{ $upcomingEvent->client->full_name ?? 'Unknown Client' }}</td>
                  <td>{{$upcomingEvent->organizer->full_name ?? 'Unknown Organizer'}}</td>

                  <td>
                    @php
                      $badge = [
                        'upcoming' => 'info',
                        'ongoing' => 'success',
                        'completed' => 'primary',
                        'cancelled' => 'danger'
                      ][$upcomingEvent->status] ?? 'secondary';
                    @endphp

                    <span class="badge bg-label-{{ $badge }}">
                      {{ ucfirst($upcomingEvent->status) }}
                    </span>
                  </td>
                </tr>

                @empty
                <tr>
                  <td colspan="5" class="text-center text-muted py-4">
                    No upcoming events found.
                  </td>
                </tr>
                @endforelse
              </tbody>

            </table>
          </div>
        </div>
      </div>
      <div class="col-xxl-6 mb-6 order-2">
        <div class="card">
          <div class="table-responsive">
            <table class="table mb-0">  
              <thead>
                <tr>
                  <th>Cancelled Events</th>
                  <th>Organizer</th>
                  <th>Client</th>
                  <th>Status</th>
                </tr>
              </thead>

              <tbody>
                @forelse ($cancelledEvents as $cancelledEvent)
                <tr>
                  <td>
                    <i class="icon-base bx bxs-calendar-event text-primary me-1 "></i>
                    <span class="fw-medium">{{ $cancelledEvent->title }}</span>
                    <br>
                    <small class="text-muted">
                      {{ \Carbon\Carbon::parse($cancelledEvent->start_date)->format('M d, Y h:i A') }}
                    </small>
                  </td>

                  <td>{{ $cancelledEvent->client->full_name ?? 'Unknown Client' }}</td>
                  <td>{{$cancelledEvent->organizer->full_name ?? 'Unknown Organizer'}}</td>

                  <td>
                    @php
                      $badge = [
                        'upcoming' => 'info',
                        'ongoing' => 'success',
                        'completed' => 'primary',
                        'cancelled' => 'danger'
                      ][$cancelledEvent->status] ?? 'secondary';
                    @endphp

                    <span class="badge bg-label-{{ $badge }}">
                      {{ ucfirst($cancelledEvent->status) }}
                    </span>
                  </td>
                </tr>

                @empty
                <tr>
                  <td colspan="5" class="text-center text-muted py-4">
                    No upcoming events found.
                  </td>
                </tr>
                @endforelse
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