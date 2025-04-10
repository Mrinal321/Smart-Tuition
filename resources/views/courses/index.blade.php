@extends('layout', ['title' => 'All Courses'])

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

{{-- Page Content --}}
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold text-primary mb-3">Explore Our Courses</h1>
        <p class="lead text-muted">Learn from industry experts and enhance your skills</p>
    </div>

    @auth
        @php
            $isTeacher = $teachers->pluck('user_teacher_id')->contains(auth()->id());
            $teacherID = $teachers->firstWhere('user_teacher_id', auth()->id())?->id;
            $teacherCourses = $courses->where('teacher_id', $teacherID);
        @endphp
        @if ($isTeacher)
        <div class="mb-5 text-center">
            <h5 class="btn btn-outline-info">Your Courses ({{ $teacherCourses->count() }})</h5>
            <a href="{{ route('courses.create', $teacherID) }}" class="btn btn-primary ms-2">
                <i class="bi bi-plus-circle"></i> Create New Course
            </a>
        </div>

        <div class="row g-4 mb-5">
            @foreach($teacherCourses as $course)
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-lg hover-shadow">
                    <div class="card-img-top gradient-header">
                        <div class="h-100 d-flex align-items-center justify-content-center">
                            <h4 class="text-white mb-0">{{ $course->course_name }}</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="card-text text-muted">{{ Str::limit($course->description, 100) }}</p>
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ asset('uploads/teacherprofile/' . $course->teacher->profile_picture) }}"
                                 alt="{{ $course->teacher->name }}"
                                 class="rounded-circle me-2"
                                 width="36" height="36">
                            <span class="fw-semibold text-secondary">Author: {{ $course->teacher->name }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-light text-primary fs-6">
                                ${{ number_format($course->updated_fee, 2) }}
                                @if($course->discount > 0)
                                    <small class="text-decoration-line-through text-danger ms-1">
                                        ${{ number_format($course->original_fee, 2) }}
                                    </small>
                                @endif
                            </span>
                            <span class="badge bg-success bg-opacity-10 text-success">
                                <i class="bi bi-people-fill me-1"></i> 24 students
                            </span>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 px-3 pb-4">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('courses.show', $course->id) }}" class="btn btn-outline-primary w-100 me-2 hover-effect">
                                <i class="bi bi-eye-fill me-1"></i> View
                            </a>
                            <a href="{{ route('courses.edit', $course->id) }}" class="btn btn-outline-success hover-effect">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    @endauth

    @if(!$courses->isEmpty())
    <div class="text-center mb-4">
        <h5 class="btn btn-outline-secondary">Available Courses ({{ $courses->count() }})</h5>
    </div>
    @endif

    <div class="row g-4">
        @foreach($courses as $course)
        <div class="col-lg-4 col-md-6">
            <div class="card h-100 border-0 shadow-sm hover-shadow">
                <div class="card-img-top gradient-header">
                    <div class="h-100 d-flex align-items-center justify-content-center">
                        <h4 class="text-white mb-0">{{ $course->course_name }}</h4>
                    </div>
                </div>
                <div class="card-body">
                    <p class="card-text text-muted">{{ Str::limit($course->description, 100) }}</p>
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ asset('uploads/teacherprofile/' . $course->teacher->profile_picture) }}"
                             alt="{{ $course->teacher->name }}"
                             class="rounded-circle me-2"
                             width="36" height="36">
                        <span class="fw-semibold text-secondary">Author: {{ $course->teacher->name }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge bg-light text-primary fs-6">
                            ${{ number_format($course->updated_fee, 2) }}
                            @if($course->discount > 0)
                                <small class="text-decoration-line-through text-danger ms-1">
                                    ${{ number_format($course->original_fee, 2) }}
                                </small>
                            @endif
                        </span>
                        <span class="badge bg-success bg-opacity-10 text-success">
                            <i class="bi bi-people-fill me-1"></i> 24 students
                        </span>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 px-3 pb-4">
                    <a href="{{ route('courses.show', $course->id) }}"
                       class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center hover-effect">
                       <i class="bi bi-eye-fill me-2"></i> View Details
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @if($courses->isEmpty())
    <div class="text-center py-5">
        <div class="display-1 text-muted"><i class="bi bi-book"></i></div>
        <h2 class="mt-3">No Courses Available</h2>
        <p class="lead text-muted">Check back later for new additions</p>
    </div>
    @endif
</div>

{{-- Extra Styles --}}
<style>
    .hover-shadow {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .hover-shadow:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1) !important;
    }

    .gradient-header {
        height: 70px;
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        border-top-left-radius: 0.5rem;
        border-top-right-radius: 0.5rem;
    }

    .hover-effect {
        transition: all 0.2s ease-in-out;
    }

    .hover-effect:hover {
        background-color: #e0f2ff;
        transform: scale(1.03);
    }
</style>

@endsection
