@extends('layout', ['title'=> 'Home'])
@section('page-content')


{{-- Navigation Bar Start --}}
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container-fluid">
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-between" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('teacher.index') ? 'active' : '' }}" href="{{route('teacher.index')}}">
                        <i class="bi bi-house-door-fill me-1"></i> Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('courses.index') ? 'active' : '' }}" href="{{route('courses.index')}}">
                        <i class="bi bi-book-fill me-1"></i> Courses
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('events.index') ? 'active' : '' }}" href="{{route('events.index')}}">
                        <i class="bi bi-calendar-event-fill me-1"></i> Events
                    </a>
                </li>
            </ul>

            <div class="d-flex align-items-center gap-2">
                @guest
                    <span class="text-white me-2">Login to create or rate!</span>
                    <a href="{{route('login')}}" class="btn btn-outline-light btn-sm px-3">Login</a>
                    <a href="{{route('register')}}" class="btn btn-light btn-sm px-3">Register</a>
                @else
                    @php
                        $isUserPresent = $teachers->pluck('user_teacher_id')->contains(auth()->id());
                        $teacherID = $teachers->firstWhere('user_teacher_id', auth()->id())?->id;
                    @endphp
                    @if ($isUserPresent)
                        <a class="btn btn-success btn-sm px-3" href="{{route('teacher.edit', $teacherID)}}">
                            <i class="bi bi-person-gear me-1"></i> {{ Auth::user()->name }}
                        </a>
                    @else
                        <a class="btn btn-outline-light btn-sm px-3" href="{{route('teacher.create')}}">
                            <i class="bi bi-person-plus me-1"></i> Enroll as Teacher
                        </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-light btn-sm px-3">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                @endguest
            </div>
        </div>
    </div>
</nav>
{{-- Navigation Bar End --}}

<div class="container my-5">
    <div class="hero-section text-center mb-5">
        <h1 class="display-4">Welcome to Event Schedule</h1>
        <p class="lead">Track all your educational events in one place</p>
    </div>
    @auth
        @php
            $isTeacher = $teachers->pluck('user_teacher_id')->contains(auth()->id());
        @endphp
        
        @if ($isTeacher)
            <a href="{{ route('events.create', $isTeacher) }}" class="btn btn-lg btn-light mt-3">
                <i class="fas fa-plus-circle"></i> Create New Event
            </a>
        @endif
    @endauth

    <div class="countdown-card card mb-4 bg-primary text-white">
        <div class="card-body text-center">
            <h2 id="countdown-title" class="card-title">Next Event Starts In:</h2>
            <div id="countdown" class="display-4 font-weight-bold">
                <span class="days">00</span>d 
                <span class="hours">00</span>h 
                <span class="minutes">00</span>m 
                <span class="seconds">00</span>s
            </div>
        </div>
    </div>

    <nav class="nav nav-tabs" id="eventTabs" role="tablist">
        <a class="nav-item nav-link active" id="running-tab" data-toggle="tab" href="#running" role="tab">Running Now</a>
        <a class="nav-item nav-link" id="upcoming-tab" data-toggle="tab" href="#upcoming" role="tab">Upcoming</a>
        <a class="nav-item nav-link" id="previous-tab" data-toggle="tab" href="#previous" role="tab">Previous</a>
    </nav>

    <div class="tab-content" id="eventTabsContent">
        <!-- Running Events Tab -->
        <div class="tab-pane fade show active" id="running" role="tabpanel">
            @if($running->isEmpty())
                <div class="alert alert-info mt-3">No events currently running</div>
            @else
                <div class="table-responsive mt-3">
                    <table class="table table-hover table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">Title</th>
                                <th scope="col">Description</th>
                                <th scope="col">Duration</th>
                                <th scope="col">Start Time</th>
                                <th scope="col">End Time</th>
                                <th scope="col">Event Setter</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($running as $schedule)
                            <tr>
                                <td>{{ $schedule->title }}</td>
                                <td>
                                    <a href="{{ route('events.index') }}" class="text-decoration-underline">
                                        {{ $schedule->description}}
                                    </a>
                                </td>
                                <td>{{ $schedule->duration }}</td>
                                <td>{{ \Carbon\Carbon::parse($schedule->start_time)->format('M d, Y H:i') }}</td>
                                <td>{{ \Carbon\Carbon::parse($schedule->end_time)->format('M d, Y H:i') }}</td>
                                <td>{{ $schedule->teacher->name }}</td>
                                <td><span class="badge badge-success">Live Now</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Upcoming Events Tab -->
        <div class="tab-pane fade" id="upcoming" role="tabpanel">
            @if($upcoming->isEmpty())
                <div class="alert alert-info mt-3">No upcoming events scheduled</div>
            @else
            <div class="table-responsive mt-4">
                <table class="table table-bordered table-hover align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">Title</th>
                            <th scope="col">Description</th>
                            <th scope="col">Duration</th>
                            <th scope="col">Start Time</th>
                            <th scope="col">End Time</th>
                            <th scope="col">Event Setter</th>
                            <th scope="col">Starts In</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($upcoming as $schedule)
                            <tr data-start="{{ $schedule->start_time }}">
                                <td>{{ $schedule->title }}</td>
                                <td>
                                    <a href="{{ route('events.index') }}" class="text-decoration-underline">
                                        {{ $schedule->description}}
                                    </a>
                                </td>
                                <td>{{ $schedule->duration }}</td>
                                <td>{{ \Carbon\Carbon::parse($schedule->start_time)->format('M d, Y H:i') }}</td>
                                <td>{{ \Carbon\Carbon::parse($schedule->end_time)->format('M d, Y H:i') }}</td>
                                <td>{{ $schedule->teacher->name }}</td>
                                <td class="countdown-timer"></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @endif
        </div>

        <!-- Previous Events Tab -->
        <div class="tab-pane fade" id="previous" role="tabpanel">
            @if($previous->isEmpty())
                <div class="alert alert-info mt-3">No previous events recorded</div>
            @else
                <div class="table-responsive mt-3">
                    <table class="table table-hover table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">Title</th>
                                <th scope="col">Description</th>
                                <th scope="col">Duration</th>
                                <th scope="col">Start Time</th>
                                <th scope="col">End Time</th>
                                <th scope="col">Event Setter</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($previous as $schedule)
                            <tr>
                                <td>{{ $schedule->title }}</td>
                                <td>
                                    <a href="{{ route('events.index') }}" class="text-decoration-underline">
                                        {{ $schedule->description}}
                                    </a>
                                </td>
                                <td>{{ $schedule->duration }}</td>
                                <td>{{ \Carbon\Carbon::parse($schedule->start_time)->format('M d, Y H:i') }}</td>
                                <td>{{ \Carbon\Carbon::parse($schedule->end_time)->format('M d, Y H:i') }}</td>
                                <td>{{ $schedule->teacher->name }}</td>
                                <td><span class="badge badge-secondary">Completed</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Countdown Timer for Next Event
    function updateMainCountdown() {
        const upcomingEvents = @json($upcoming);
        if(upcomingEvents.length === 0) {
            document.getElementById('countdown-title').textContent = 'No Upcoming Events';
            document.getElementById('countdown').style.display = 'none';
            return;
        }

        const nextEvent = upcomingEvents[0];
        const startTime = new Date(nextEvent.start_time).getTime();
        const now = new Date().getTime();
        const distance = startTime - now;

        if(distance < 0) {
            clearInterval(mainCountdown);
            return;
        }

        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        document.querySelector('#countdown .days').textContent = days;
        document.querySelector('#countdown .hours').textContent = hours;
        document.querySelector('#countdown .minutes').textContent = minutes;
        document.querySelector('#countdown .seconds').textContent = seconds;
    }

    // Individual Event Countdowns
    function updateRowCountdowns() {
        document.querySelectorAll('.countdown-timer').forEach(td => {
            const startTime = new Date(td.parentElement.dataset.start).getTime();
            const now = new Date().getTime();
            const distance = startTime - now;

            if(distance < 0) {
                td.textContent = 'Event Started';
                return;
            }

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            td.textContent = `${days}d ${hours}h ${minutes}m ${seconds}s`;
        });
    }

    // Initialize timers
    let mainCountdown = setInterval(updateMainCountdown, 1000);
    let rowCountdowns = setInterval(updateRowCountdowns, 1000);
    updateMainCountdown();
    updateRowCountdowns();

    // Tab Persistence
    const activeTab = localStorage.getItem('activeTab');
    if(activeTab) {
        const tabTrigger = new bootstrap.Tab(document.querySelector(activeTab));
        tabTrigger.show();
    }

    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', function(e) {
            localStorage.setItem('activeTab', e.target.getAttribute('href'));
        });
    });
});
</script>

<style>
    .hero-section {
        padding: 4rem 2rem;
        background-color: #f8f9fa;
        border-radius: 0.5rem;
    }

    .countdown-card {
        border: none;
        border-radius: 0.5rem;
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
    }

    .table-hover tbody tr:hover {
        transform: translateX(5px);
        transition: transform 0.2s ease;
    }

    .badge {
        font-size: 0.9em;
        padding: 0.6em 1em;
    }
</style>

@endsection