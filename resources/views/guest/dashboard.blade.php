@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        
        {{-- 1. WELCOME CARD (Generic Greeting) --}}
        <div class="col-xxl-12 mb-6">
            <div class="card">
                <div class="d-flex row">
                    <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 ps-10 text-sm-center text-center">
                            {{-- Using a generic public image path --}}
                            <img src="{{ asset('assets/img/illustrations/boy-with-laptop-light.png') }}" height="181" alt="Guest Welcome">
                        </div>
                    </div>
                    <div class="col-sm-7">
                        <div class="card-body">
                            {{-- Generic greeting for the Guest --}}
                            <h5 class="card-title text-primary mb-3">Welcome to EventNexus!</h5>
                            <p class="mb-6">
                                You have {{$upcomingEvents}} upcoming invited events. Get ready!
                            </p>
                            {{-- Optional: Link to a public events list --}}
                            {{-- <a href="{{ route('guest.events.list') }}" class="btn btn-primary">View All Invitations</a> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- 2. EVENT SUMMARY CARDS --}}
        <div class="col-xxl-12 mb-6">
            <div class="card">
                <div class="card-widget-separator-wrapper">
                    <div class="card-body card-widget-separator">
                        <div class="row gy-4 gy-sm-1">
                            
                            {{-- Total Events --}}
                            <div class="col-sm-6 col-lg-3">
                                <div class="d-flex justify-content-between align-items-center card-widget-1 border-end pb-4 pb-sm-0">
                                    <div>
                                        <h4 class="mb-0">{{$totalEvents}}</h4>
                                        <p class="mb-0">Total Invited Events</p>
                                    </div>
                                    <div class="avatar me-sm-6">
                                        <span class="avatar-initial rounded bg-label-secondary">
                                            <i class="bx bx-calendar-event bx-26px"></i>
                                        </span>
                                    </div>
                                </div>
                                <hr class="d-none d-sm-block d-lg-none me-6">
                            </div>
                            
                            {{-- Completed Events --}}
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
                            
                            {{-- Upcoming Events --}}
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
                            
                            {{-- Cancelled Events --}}
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
</div>
@endsection