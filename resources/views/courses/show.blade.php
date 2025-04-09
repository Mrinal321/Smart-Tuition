@extends('layout', ['title' => $course->course_name])

@section('page-content')

<div class="container my-5">
    <!-- Course Card -->
    <div class="card shadow-lg overflow-hidden">
        <!-- Header with Gradient Background -->
        <div class="card-header bg-gradient-primary text-white py-4 position-relative">
            <div class="position-absolute top-0 end-0 mt-3 me-4">
                <img src="{{ asset('uploads/teacherprofile/' . $course->teacher->profile_picture) }}" 
                     alt="{{ $course->teacher->name }}" 
                     class="img-thumbnail rounded-circle border-white border-3 shadow-sm"
                     style="width: 80px; height: 80px; object-fit: cover;">
            </div>
            
            <div class="text-center px-4">
                <h1 class="display-5 fw-bold mb-2">{{ $course->course_name }}</h1>
                <div class="d-flex justify-content-center align-items-center">
                    <span class="me-2">Auther: </span>
                    <h4 class="mb-0 d-inline-block">
                        <a href="{{ route('teacher.profile', $course->teacher->id) }}" 
                           class="badge bg-white text-primary text-decoration-none hover-effect">
                           {{ $course->teacher->name }}
                        </a>
                    </h4>
                </div>
            </div>
        </div>

        <!-- Course Body -->
        <div class="card-body p-4 p-md-5">
            <!-- Description Section -->
            <div class="mb-5">
                <h3 class="text-primary mb-3 border-bottom pb-2">
                    <i class="bi bi-card-text me-2"></i>Course Description
                </h3>
                <p class="lead">{{ $course->description ?? 'No description available' }}</p>
            </div>

            <!-- Demo Classes Section -->
            @if($course->demo_class0 || $course->demo_class1 || $course->demo_class2)
            <div class="mb-5">
                <h3 class="text-primary mb-3 border-bottom pb-2">
                    <i class="bi bi-play-circle me-2"></i>Free Demo Classes
                </h3>
                <div class="row g-3">
                    @foreach(['demo_class0', 'demo_class1', 'demo_class2'] as $demo)
                        @if($course->$demo)
                            <div class="col-md-4">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <i class="bi bi-play-btn-fill text-primary" style="font-size: 2.5rem;"></i>
                                        <h5 class="mt-2">Demo {{ substr($demo, -1) }}</h5>
                                    </div>
                                    <div class="card-footer bg-transparent border-0">
                                        <a href="{{ $course->$demo }}" target="_blank" 
                                           class="btn btn-outline-primary w-100">
                                           Watch Now
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Resources Section -->
            <div class="mb-5">
                <h3 class="text-primary mb-3 border-bottom pb-2">
                    <i class="bi bi-file-earmark-arrow-down me-2"></i>Learning Resources
                </h3>
                @if($course->resources_link)
                    <div class="alert alert-info d-flex align-items-center">
                        <i class="bi bi-info-circle-fill me-3" style="font-size: 1.5rem;"></i>
                        <div>
                            <h5 class="alert-heading">Download Course Materials</h5>
                            <a href="{{ $course->resources_link }}" target="_blank" 
                               class="btn btn-success mt-2">
                               <i class="bi bi-download me-1"></i> Download Resources
                            </a>
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Resources will be available soon
                    </div>
                @endif
            </div>

            <!-- Pricing Section -->
            <div class="mb-5">
                <h3 class="text-primary mb-3 border-bottom pb-2">
                    <i class="bi bi-currency-dollar me-2"></i>Course Fee
                </h3>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card bg-light border-0 h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted">Original Price:</span>
                                    <span class="text-decoration-line-through text-danger fw-bold">
                                        ${{ number_format($course->original_fee, 2) }}
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted">Discount:</span>
                                    <span class="text-success fw-bold">
                                        {{ $course->discount }}%
                                    </span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="h5 mb-0">Final Price:</span>
                                    <span class="h4 text-primary fw-bold">
                                        ${{ number_format($course->updated_fee, 2) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 d-flex align-items-center">
                        <div class="w-100 text-center py-4">
                            <button class="btn btn-primary btn-lg px-5 py-3 shadow">
                                <i class="bi bi-cart-check me-2"></i> Enroll Now
                            </button>
                            <p class="small text-muted mt-2 mb-0">30-day money back guarantee</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer with Contact Info -->
        <div class="card-footer bg-light py-3">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <h5 class="mb-2">
                        <i class="bi bi-person-circle me-2"></i> Instructor Contact
                    </h5>
                    <ul class="list-unstyled">
                        <li class="mb-1">
                            <i class="bi bi-telephone me-2"></i>
                            <a href="tel:{{ $course->teacher->phone_number }}" class="text-decoration-none">
                                {{ $course->teacher->phone_number }}
                            </a>
                        </li>
                        <li>
                            <i class="bi bi-envelope me-2"></i>
                            <a href="mailto:{{ $course->teacher->email }}" class="text-decoration-none">
                                {{ $course->teacher->email }}
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-6 text-center text-md-end d-flex align-items-center justify-content-center justify-content-md-end">
                    <div class="d-flex flex-column">
                        <div class="d-flex justify-content-center justify-content-md-end mb-2">
                            <a href="#" class="text-decoration-none me-3">
                                <i class="bi bi-facebook fs-4 text-primary"></i>
                            </a>
                            <a href="#" class="text-decoration-none me-3">
                                <i class="bi bi-twitter fs-4 text-info"></i>
                            </a>
                            <a href="#" class="text-decoration-none">
                                <i class="bi bi-linkedin fs-4 text-primary"></i>
                            </a>
                        </div>
                        <span class="badge bg-secondary">
                            <i class="bi bi-people me-1"></i> 125 students enrolled
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    }
    .img-thumbnail {
        transition: transform 0.3s ease;
    }
    .img-thumbnail:hover {
        transform: scale(1.05);
    }
</style>

@endsection