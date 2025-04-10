@extends('layout', ['title'=> 'Find Your Perfect Teacher'])

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

<!-- Hero Section -->
<div class="bg-gradient-primary text-white py-5 mb-4">
    <div class="container text-center">
        <h1 class="display-4 fw-bold mb-3">Find Your Perfect Teacher</h1>
        <p class="lead">Connect with experienced educators from top universities</p>
    </div>
</div>

<!-- Filter Form -->
<div class="container mb-5">
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="GET" action="{{ route('teacher.index') }}" id="filterForm">
                <div class="row g-3 align-items-end">
                    <!-- University Filter -->
                    <div class="col-md-5">
                        <label for="university_name" class="form-label">University</label>
                        <select name="university_name" id="university_name" class="form-select">
                            <option value="">All Universities</option>
                            @foreach ($universities as $uni)
                                <option value="{{ $uni }}" {{ $uni == $university ? 'selected' : '' }}>{{ $uni }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Department Filter -->
                    <div class="col-md-5">
                        <label for="department_name" class="form-label">Department</label>
                        <select name="department_name" id="department_name" class="form-select">
                            <option value="">All Departments</option>
                            @foreach ($departments as $dept)
                                <option value="{{ $dept }}" {{ $dept == $department ? 'selected' : '' }}>{{ $dept }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Buttons -->
                    <div class="col-md-2 d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-funnel-fill me-1"></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Teachers List -->
<div class="container mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Available Teachers</h2>
        <div class="text-muted">{{ $teachers->count() }} teachers found</div>
    </div>

    @if($teachers->isEmpty())
        <div class="text-center py-5">
            <div class="display-1 text-muted mb-3">
                <i class="bi bi-people"></i>
            </div>
            <h3>No teachers found</h3>
            <p class="lead">Try adjusting your filters or check back later</p>
        </div>
    @else
        <div class="row g-4" id="teachersContainer">
            @foreach($teachers as $item)
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm teacher-card">
                    <div class="card-body text-center p-4">
                        <!-- Profile Picture -->
                        <div class="position-relative mx-auto" style="width: 120px;">
                            <img src="{{ asset('uploads/teacherprofile/'.$item->profile_picture) }}" 
                                 alt="{{ $item->name }}" 
                                 class="rounded-circle border border-4 border-primary object-fit-cover"
                                 style="width: 120px; height: 120px;">
                            <div class="position-absolute bottom-0 end-0 bg-white rounded-circle p-1 shadow-sm">
                                <i class="bi bi-patch-check-fill text-primary fs-5"></i>
                            </div>
                        </div>

                        <!-- Teacher Info -->
                        <h4 class="mt-4 mb-1">{{ $item->name }}</h4>
                        <div class="text-muted mb-3">
                            <div><i class="bi bi-building me-1"></i> {{ $item->university_name }}</div>
                            <div><i class="bi bi-book me-1"></i> {{ $item->department_name }}</div>
                        </div>

                        <!-- Rating -->
                        @php
                            $number = 0; $str = 0;
                            if($item->star_count > 0){
                                $str = ($item->total_star / $item->star_count);
                                $number = ceil($str);
                            }
                        @endphp
                        <div class="mb-3">
                            <div class="d-flex justify-content-center align-items-center mb-1">
                                <div class="star-rating me-2" data-rating="{{ $str }}">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $number)
                                            <i class="bi bi-star-fill text-warning"></i>
                                        @else
                                            <i class="bi bi-star text-warning"></i>
                                        @endif
                                    @endfor
                                </div>
                                <span class="fw-bold">{{ number_format($str, 1) }}</span>
                            </div>
                            <small class="text-muted">({{ $item->star_count }} reviews)</small>
                        </div>

                        <!-- View Profile Button -->
                        <a href="{{ route('teacher.profile', $item->id) }}" 
                           class="btn btn-outline-primary w-100 stretched-link">
                           <i class="bi bi-person-lines-fill me-1"></i> View Profile
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>

<!-- Back to Top Button -->
<button id="backToTop" class="btn btn-primary btn-lg rounded-circle shadow-lg position-fixed bottom-0 end-0 m-4 d-none">
    <i class="bi bi-arrow-up"></i>
</button>

<!-- JavaScript Enhancements -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Back to Top Button
    const backToTopButton = document.getElementById('backToTop');
    
    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            backToTopButton.classList.remove('d-none');
        } else {
            backToTopButton.classList.add('d-none');
        }
    });
    
    backToTopButton.addEventListener('click', function() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    // Teacher Card Hover Effect
    const teacherCards = document.querySelectorAll('.teacher-card');
    
    teacherCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
            this.style.transition = 'transform 0.2s ease, box-shadow 0.2s ease';
            this.style.boxShadow = '0 10px 20px rgba(0,0,0,0.1)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = '';
            this.style.boxShadow = '';
        });
    });

    // Filter Form Submission with Loading Indicator
    const filterForm = document.getElementById('filterForm');
    const teachersContainer = document.getElementById('teachersContainer');
    
    if(filterForm && teachersContainer) {
        filterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Show loading state
            teachersContainer.innerHTML = `
                <div class="col-12 text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Finding matching teachers...</p>
                </div>
            `;
            
            // Submit form via AJAX
            fetch(this.action + '?' + new URLSearchParams(new FormData(this)), {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newContent = doc.getElementById('teachersContainer').innerHTML;
                teachersContainer.innerHTML = newContent;
            })
            .catch(error => {
                teachersContainer.innerHTML = `
                    <div class="col-12 text-center py-5 text-danger">
                        <i class="bi bi-exclamation-triangle-fill fs-1"></i>
                        <p class="mt-2">Error loading results. Please try again.</p>
                    </div>
                `;
            });
        });
    }
});
</script>

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    }
    .teacher-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .teacher-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .object-fit-cover {
        object-fit: cover;
    }
    #backToTop {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }
    .star-rating {
        display: inline-flex;
        font-size: 1.25rem;
    }
</style>

@endsection