@extends('layouts.app')
@section('title', 'Organizer Dashboard | EventNexus')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-xxl-12 mb-6">
            <div class="card">
                <div class="d-flex row">
                    <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 ps-10 text-sm-center text-center">
                          <img src="../../assets/img/illustrations/boy-with-laptop-light.png" height="181" alt="Target User">
                        </div>
                    </div>
                    <div class="col-sm-7">
                        <div class="card-body">
                          <h5 class="card-title text-primary mb-3">Welcome back {{Auth::user()->firstname}}!</h5>
                          <p class="mb-6">
                            You have {{$upcomingEvents}} upcoming events. Break a leg!
                          </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-12 mb-6">
            <div class="card">
                    <div class="card-widget-separator-wrapper">
                      <div class="card-body card-widget-separator">
                        <div class="row gy-4 gy-sm-1">
                          <div class="col-sm-6 col-lg-3">
                            <div class="d-flex justify-content-between align-items-center card-widget-1 border-end pb-4 pb-sm-0">
                              <div>
                                <h4 class="mb-0">{{$totalEvents}}</h4>
                                <p class="mb-0">Total Events</p>
                              </div>
                              <div class="avatar me-sm-6">
                                <span class="avatar-initial rounded bg-label-secondary">
                                  <i class="bx bx-calendar-event bx-26px"></i>
                                </span>
                              </div>
                            </div>
                            <hr class="d-none d-sm-block d-lg-none me-6">
                          </div>
                          <div class="col-sm-6 col-lg-3">
                            <div class="d-flex justify-content-between align-items-center card-widget-2 border-end pb-4 pb-sm-0">
                              <div>
                                <h4 class="mb-0 text-success">{{$completedEvents}}</h4>
                                <p class="mb-0 text-success">Completed Events</p>
                              </div>
                              <div class="avatar me-lg-6">
                                <span class="avatar-initial rounded bg-label-success">
                                  <i class="bx bx-calendar-check bx-26px"></i>
                                </span>
                              </div>
                            </div>
                            <hr class="d-none d-sm-block d-lg-none">
                          </div>
                          <div class="col-sm-6 col-lg-3">
                            <div class="d-flex justify-content-between align-items-center border-end pb-4 pb-sm-0 card-widget-3">
                              <div>
                                <h4 class="mb-0 text-primary">{{$upcomingEvents}}</h4>
                                <p class="mb-0 text-primary">Upcoming Events</p>
                              </div>
                              <div class="avatar me-sm-6">
                                <span class="avatar-initial rounded bg-label-primary text-heading">
                                  <i class="bx bx-calendar-week bx-26px"></i>
                                </span>
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-6 col-lg-3">
                            <div class="d-flex justify-content-between align-items-center">
                              <div>
                                <h4 class="mb-0 text-danger">{{$cancelledEvents}}</h4>
                                <p class="mb-0 text-danger">Cancelled Events</p>
                              </div>
                              <div class="avatar">
                                <span class="avatar-initial rounded bg-label-danger">
                                  <i class="bx bx-calendar-x bx-26px"></i>
                                </span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
        </div>
    </div>

    <div class="row">
    
    <div class="col-md-6 col-lg-7 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title m-0 me-2">Upcoming Schedule</h5>
                <a href="{{ route('organizer.events') }}" class="small fw-bold">View All</a>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    @forelse($upcomingSchedule as $event)
                        <li class="list-group-item p-0 mb-4 border-0">
                            <div class="d-flex">
                                <div class="d-flex flex-column align-items-center justify-content-center rounded bg-label-primary me-3" style="width: 50px; height: 50px;">
                                    <span class="fw-bold" style="font-size: 1.1rem;">{{ $event->start_date->format('d') }}</span>
                                    <small class="text-uppercase" style="font-size: 0.7rem;">{{ $event->start_date->format('M') }}</small>
                                </div>
                                
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0">{{ $event->title }}</h6>
                                        <small class="text-muted">
                                            <i class="bx bx-time-five me-1"></i> {{ $event->start_date->format('h:i A') }}
                                            <span class="mx-1">|</span>
                                            <i class="bx bx-map me-1"></i> {{ $event->venue ?? 'TBD' }}
                                        </small>
                                    </div>
                                    <span class="badge bg-label-info">
                                        {{ $event->capacity ? $event->capacity . ' Seats' : 'Unlimited' }}
                                    </span>
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="text-center text-muted py-5">
                            <i class="bx bx-calendar-x mb-2" style="font-size: 2rem;"></i>
                            <p>No upcoming events scheduled.</p>
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-5 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title m-0">Recent Clients</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    @forelse($recentClients as $client)
                        @if($client)
                        <li class="list-group-item d-flex align-items-center justify-content-between p-0 mb-4 border-0">
                            
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm me-3">
                                    <span class="avatar-initial rounded-circle bg-label-success">
                                        {{ substr($client->firstname, 0, 1) }}
                                    </span>
                                </div>
                                <div>
                                    <h6 class="mb-0 text-truncate" style="max-width: 180px;">
                                        {{ $client->firstname }} {{ $client->lastname }}
                                    </h6>
                                    <small class="text-muted">{{ $client->email }}</small>
                                </div>
                            </div>

                            <div>
                                <a href="mailto:{{ $client->email }}" class="btn btn-sm btn-icon btn-outline-primary">
                                    <i class="bx bx-envelope"></i>
                                </a>
                            </div>
                        </li>
                        @endif
                    @empty
                        <li class="text-center text-muted py-5">
                            <i class="bx bx-user-x mb-2" style="font-size: 2rem;"></i>
                            <p>No recent clients found.</p>
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
