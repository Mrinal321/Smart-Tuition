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
                            <div class="profile-picture-container mb-4">
                                <img src="{{ asset('uploads/teacherprofile/'.$item->profile_picture) }}" 
                                     alt="{{ $item->name }}" 
                                     class="profile-picture circle">
                                <div class="verification-badge">
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
                                <!-- Check if User Can Rate -->
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
document.addEventListener('DOMContentLoaded', function() {
    // Star Rating Interaction
    const starInputs = document.querySelectorAll('.star-rating input');
    const ratingSubmit = document.getElementById('rating-submit');
    const ratingForm = document.getElementById('rating-form');
    
    if (starInputs.length > 0) {
        starInputs.forEach(input => {
            input.addEventListener('change', function() {
                // Enable the submit button when a rating is selected
                if (ratingSubmit) {
                    ratingSubmit.disabled = false;
                }
                
                // Update visual display
                const rating = this.value;
                const labels = document.querySelectorAll('.star-rating label');
                
                labels.forEach((label, index) => {
                    const starIcon = label.querySelector('i');
                    if (index < 5 - rating) {
                        starIcon.classList.remove('fas');
                        starIcon.classList.add('far');
                    } else {
                        starIcon.classList.remove('far');
                        starIcon.classList.add('fas');
                    }
                });
            });
            
            // Hover effects
            input.addEventListener('mouseover', function() {
                const hoverRating = this.value;
                const labels = document.querySelectorAll('.star-rating label');
                
                labels.forEach((label, index) => {
                    const starIcon = label.querySelector('i');
                    if (index < 5 - hoverRating) {
                        starIcon.classList.remove('fas');
                        starIcon.classList.add('far');
                    } else {
                        starIcon.classList.remove('far');
                        starIcon.classList.add('fas');
                    }
                    starIcon.style.color = '#ffc107';
                });
            });
            
            input.addEventListener('mouseout', function() {
                const checkedInput = document.querySelector('.star-rating input:checked');
                const currentRating = checkedInput ? checkedInput.value : 0;
                const labels = document.querySelectorAll('.star-rating label');
                
                labels.forEach((label, index) => {
                    const starIcon = label.querySelector('i');
                    if (index < 5 - currentRating) {
                        starIcon.classList.remove('fas');
                        starIcon.classList.add('far');
                    } else {
                        starIcon.classList.remove('far');
                        starIcon.classList.add('fas');
                    }
                    starIcon.style.color = '';
                });
            });
        });
        
        // Form submission handler
        if (ratingForm) {
            ratingForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const url = this.action;
                
                fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        const ratingSection = document.querySelector('.rating-section');
                        ratingSection.innerHTML = `
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i>
                                Thank you for rating this teacher!
                                <div class="mt-2">
                                    ${generateStars(data.rating)}
                                </div>
                            </div>
                        `;
                        
                        // Update the average rating display
                        if (data.average_rating) {
                            updateAverageRating(data.average_rating, data.total_ratings);
                        }
                    } else {
                        alert('Error: ' + (data.message || 'Failed to submit rating'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while submitting your rating.');
                });
            });
        }
    }
    
    // Profile picture hover effect
    const profilePicture = document.querySelector('.profile-picture');
    if (profilePicture) {
        profilePicture.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.05)';
            this.style.boxShadow = '0 5px 15px rgba(0,0,0,0.3)';
        });
        
        profilePicture.addEventListener('mouseleave', function() {
            this.style.transform = '';
            this.style.boxShadow = '';
        });
    }
    
    // Helper function to generate star HTML
    function generateStars(rating) {
        let stars = '';
        for (let i = 1; i <= 5; i++) {
            if (i <= rating) {
                stars += '<i class="fas fa-star text-warning"></i>';
            } else {
                stars += '<i class="far fa-star text-warning"></i>';
            }
        }
        return stars;
    }
    
    // Helper function to update average rating display
    function updateAverageRating(averageRating, totalRatings) {
        const averageRatingEl = document.querySelector('.average-rating');
        const starsEl = document.querySelector('.stars');
        const ratingCountEl = document.querySelector('.text-muted.small');
        
        if (averageRatingEl) {
            averageRatingEl.innerHTML = `
                <span class="display-4 fw-bold">${averageRating}</span>
                <span class="text-muted">/5</span>
            `;
        }
        
        if (starsEl) {
            const fullStars = Math.floor(averageRating);
            const hasHalfStar = (averageRating - fullStars) >= 0.5;
            let starsHTML = '';
            
            for (let i = 1; i <= 5; i++) {
                if (i <= fullStars) {
                    starsHTML += '<i class="fas fa-star text-warning"></i>';
                } else if (i === fullStars + 1 && hasHalfStar) {
                    starsHTML += '<i class="fas fa-star-half-alt text-warning"></i>';
                } else {
                    starsHTML += '<i class="far fa-star text-warning"></i>';
                }
            }
            
            starsEl.innerHTML = starsHTML;
        }
        
        if (ratingCountEl) {
            ratingCountEl.textContent = `${totalRatings} ratings`;
        }
    }
});
</script>
@endpush

@push('styles')
<style>
    /* (Keep all the previous CSS styles from the earlier example) */
    .teacher-profile-card {
        border-radius: 1rem;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    /* ... (rest of the CSS remains the same) ... */
</style>
@endpush

@endsection