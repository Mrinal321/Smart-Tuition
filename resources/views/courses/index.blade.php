@extends('layout', ['title' => 'All Courses'])

@section('page-content')

<div class="container py-5">
    <!-- Page Header -->
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold text-primary mb-3">Explore Our Courses</h1>
        <p class="lead text-muted">Learn from industry experts and enhance your skills</p>
        
        <!-- Create Course Button (for teachers) -->
        @auth
            @php
                $isTeacher = $teachers->pluck('user_teacher_id')->contains(auth()->id());
                $teacherID = $teachers->firstWhere('user_teacher_id', auth()->id())?->id;
            @endphp
            @if ($isTeacher)
                <div class="mt-4">
                    <a href="{{ route('courses.create', $teacherID) }}" class="btn btn-primary btn-lg px-4">
                        <i class="bi bi-plus-circle me-2"></i> Create New Course
                    </a>
                </div>
            @endif
        @endauth
    </div>

    <!-- Courses Grid -->
    <div class="row g-4">
        @foreach($courses as $course)
        <div class="col-lg-4 col-md-6">
            <div class="card h-100 border-0 shadow-sm hover-shadow transition-all">
                <!-- Course Image Placeholder (you can add actual course images) -->
                <div class="card-img-top bg-gradient-primary" style="height: 60px; background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);">
                    <div class="h-100 d-flex align-items-center justify-content-center">
                        <h3 class="text-white mb-0">{{ $course->course_name}}</h3>
                        <h5 class="text-white mb-0"> Auther: {{ $course->course_name}}</h5>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Author Info -->
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ asset('uploads/teacherprofile/' . $course->teacher->profile_picture) }}" 
                             alt="{{ $course->teacher->name }}" 
                             class="rounded-circle me-2" 
                             width="32" height="32">
                        <small class="text-muted">By {{ $course->teacher->name }}</small>
                    </div>
                    
                    <!-- Course Title -->
                    <h5 class="card-title fw-bold">{{ $course->course_name }}</h5>
                    
                    <!-- Course Description -->
                    <p class="card-text text-muted mb-4">{{ Str::limit($course->description, 100) }}</p>
                    
                    <!-- Price Badge -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
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
                
                <!-- Card Footer -->
                <div class="card-footer bg-transparent border-0 pt-0 pb-3">
                    <a href="{{ route('courses.show', $course->id) }}" 
                       class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center">
                       <i class="bi bi-eye-fill me-2"></i> View Details
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Empty State -->
    @if($courses->isEmpty())
    <div class="text-center py-5">
        <div class="display-1 text-muted">
            <i class="bi bi-book"></i>
        </div>
        <h2 class="mt-4">No Courses Available</h2>
        <p class="lead text-muted">Check back later for new courses</p>
    </div>
    @endif
</div>

<style>
    .hover-shadow {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
</style>

@endsection