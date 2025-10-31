@extends('layout', ['title' => $item->name . ' - Teacher Profile'])

@section('page-content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <!-- Teacher Profile Card -->
            <div class="card teacher-profile-card shadow-lg border-0">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <!-- Profile Picture Column -->
                        <div class="col-md-4 text-center">
                            <div class="profile-picture-container mb-4 position-relative" style="display:inline-block;">
                                <img src="{{ asset('uploads/teacherprofile/'.$item->profile_picture) }}" 
                                     alt="{{ $item->name }}" 
                                     class="profile-picture circle border border-3 border-primary" 
                                     style="width:150px; height:150px; object-fit:cover; border-radius:50%;">
                                <div class="verification-badge position-absolute top-0 start-100 translate-middle" style="font-size:1.5rem; color:#28a745;">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                            
                            <!-- Rating Display -->
                            <div class="rating-display mb-3">
                                <div class="average-rating">
                                    @php
                                        $rating = $item->star_count > 0 ? number_format($item->total_star / $item->star_count, 1) : 0;
                                        $fullStars = floor($rating);
                                        $hasHalfStar = ($rating - $fullStars) >= 0.5;
                                        $userRating = auth()->check() ? $item->ratings->where('user_id', auth()->id())->first() : null;
                                    @endphp
                                    <span class="display-4 fw-bold">{{ $rating }}</span>
                                    <span class="text-muted">/5</span>
                                </div>
                                <div class="stars mb-2">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $fullStars)
                                            <i class="fas fa-star text-warning"></i>
                                        @elseif ($i == $fullStars + 1 && $hasHalfStar)
                                            <i class="fas fa-star-half-alt text-warning"></i>
                                        @else
                                            <i class="far fa-star text-warning"></i>
                                        @endif
                                    @endfor
                                </div>
                                <div class="text-muted small">
                                    {{ $item->star_count }} ratings
                                </div>
                            </div>
                        </div>
                        
                        <!-- Teacher Info Column -->
                        <div class="col-md-8">
                            <h1 class="teacher-name mb-3">{{ $item->name }}</h1>
                            
                            <div class="teacher-meta mb-4">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-university me-2 text-primary"></i>
                                    <span>{{ $item->university_name }}</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-graduation-cap me-2 text-primary"></i>
                                    <span>{{ $item->department_name }}</span>
                                </div>
                                <!-- Message Button aligned with Email/Call/Profile -->
                                <div class="d-flex align-items-center mb-3" style="gap:0.5rem;">
                                    <input type="text" class="form-control" placeholder="Message Here" style="flex:1; min-width:0;">
                                    <button class="btn btn-success">Send</button>
                                </div>
                                
                                <div class="d-flex flex-wrap gap-3 mt-3">
                                    <a href="mailto:{{ $item->email }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-envelope me-1"></i> Email
                                    </a>
                                    <a href="tel:{{ $item->phone_number }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-phone me-1"></i> Call
                                    </a>
                                    @if (!empty($item->social_media_link))
                                    <a href="{{ $item->social_media_link }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fab fa-facebook-f me-1"></i> Profile
                                    </a>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Rating Section -->
                            <div class="rating-section mt-4 pt-3 border-top">
                                @if(auth()->check() && !$item->voters->contains(auth()->id()))
                                    <form action="{{ route('teachers.rate', $item->id) }}" method="POST">
                                        @csrf
                                        <label for="rating">Rate this teacher:</label>
                                        <div id="rating">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <label>
                                                    <input type="radio" name="rating" value="{{ $i }}" style="display: none;">
                                                    <i class="fa fa-star"
                                                    onclick="this.closest('form').submit()"
                                                    style="cursor: pointer; color: gray;"
                                                    onmouseover="this.style.color='gold'"
                                                    onmouseout="this.style.color='gray'"></i>
                                                </label>
                                            @endfor
                                        </div>
                                    </form>
                                @else
                                    @if(auth()->check())
                                        <p>You have already rated this teacher.</p>
                                    @else
                                        <p><a href="{{ route('login') }}">Login</a> to rate this teacher.</p>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Info Footer -->
                <div class="card-footer bg-light">
                    <div class="row text-center">
                        <div class="col-md-4 border-end py-2">
                            <div class="text-muted small">Courses Taught</div>
                            <div class="h5 mb-0">{{ $item->courses_count ?? 0 }}</div>
                        </div>
                        <div class="col-md-4 border-end py-2">
                            <div class="text-muted small">Events Hosted</div>
                            <div class="h5 mb-0">{{ $item->events_count ?? 0 }}</div>
                        </div>
                        <div class="col-md-4 py-2">
                            <div class="text-muted small">Years Teaching</div>
                            <div class="h5 mb-0">{{ now()->diffInYears($item->created_at) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Profile picture hover effect
const profilePicture = document.querySelector('.profile-picture');
if(profilePicture){
    profilePicture.addEventListener('mouseenter', ()=>{
        profilePicture.style.transform='scale(1.05)';
        profilePicture.style.boxShadow='0 5px 15px rgba(0,0,0,0.3)';
    });
    profilePicture.addEventListener('mouseleave', ()=>{
        profilePicture.style.transform='';
        profilePicture.style.boxShadow='';
    });
}
</script>
@endpush

@push('styles')
<style>
.teacher-profile-card { border-radius:1rem; overflow:hidden; transition: transform 0.3s ease, box-shadow 0.3s ease; }
.profile-picture-container .verification-badge { border-radius:50%; background:#fff; }
</style>
@endpush

@endsection
