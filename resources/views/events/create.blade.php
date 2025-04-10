@extends('layout', ['title' => 'Create New Event'])

@section('page-content')
<div class="container py-5">
    <div class="card shadow-lg rounded-4">
        <div class="card-header bg-primary text-white py-3 rounded-top-4">
            <h2 class="h4 mb-0"><i class="fas fa-calendar-plus me-2"></i>Schedule New Class</h2>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('events.store') }}" method="POST" id="eventForm">
                @csrf
                <input type="hidden" name="teacher_id" value="{{ $teacher->id }}">

                <div class="row g-4">
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <!-- Class Title -->
                        <div class="form-floating">
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title') }}" 
                                   placeholder="Class Title" required>
                            <label for="title"><i class="fas fa-heading me-2"></i>Class Title</label>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="form-floating mt-3">
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" style="height: 120px"
                                      placeholder="Class Description">{{ old('description') }}</textarea>
                            <label for="description"><i class="fas fa-align-left me-2"></i>Description</label>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6">
                        <!-- Duration & Time -->
                        <div class="form-floating">
                            <input type="number" class="form-control @error('duration') is-invalid @enderror" 
                                   id="duration" name="duration" value="{{ old('duration') }}" 
                                   placeholder="Duration" min="1" required>
                            <label for="duration"><i class="fas fa-clock me-2"></i>Duration (minutes)</label>
                            @error('duration')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <label class="form-label"><i class="fas fa-calendar-alt me-2"></i>Schedule (Dhaka Time)</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="datetime-local" 
                                           class="form-control @error('start_time') is-invalid @enderror" 
                                           id="start_time" name="start_time" value="{{ old('start_time') }}" required>
                                    <small class="text-muted">Start Time</small>
                                </div>
                                <div class="col-6">
                                    <input type="datetime-local" 
                                           class="form-control @error('end_time') is-invalid @enderror" 
                                           id="end_time" name="end_time" value="{{ old('end_time') }}" required>
                                    <small class="text-muted">End Time</small>
                                </div>
                            </div>
                            <div id="timeValidation" class="text-danger small mt-1"></div>
                        </div>

                        <!-- Teacher Card -->
                        <div class="card border-info mt-4">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-3">
                                        <img src="{{ asset('uploads/teacherprofile/'.$teacher->profile_picture) }}" 
                                             class="rounded-circle" width="50" height="50">
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Instructor Profile</h6>
                                        <p class="small mb-0 text-muted">
                                            {{ $teacher->name }}<br>
                                            {{ $teacher->university_name }} - {{ $teacher->department_name }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="col-12 mt-4">
                        <div class="d-flex justify-content-end gap-3">
                            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary px-4 rounded-3">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary px-4 rounded-3">
                                <i class="fas fa-calendar-check me-2"></i>Schedule Class
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Timezone Handling
    const dhakaOffset = 6 * 60; // UTC+6 in minutes
    const now = new Date();
    now.setMinutes(now.getMinutes() + now.getTimezoneOffset() + dhakaOffset);

    // Format datetime for input
    const formatDateTime = (date) => {
        return date.toISOString().slice(0, 16);
    };

    // Set default times
    const startTime = now;
    const endTime = new Date(now.getTime() + 60 * 60 * 1000);
    
    document.getElementById('start_time').value = formatDateTime(startTime);
    document.getElementById('end_time').value = formatDateTime(endTime);
    document.getElementById('duration').value = 60;

    // Time Validation
    const timeValidation = document.getElementById('timeValidation');
    const validateTimes = () => {
        const start = new Date(document.getElementById('start_time').value);
        const end = new Date(document.getElementById('end_time').value);
        
        if (start >= end) {
            timeValidation.textContent = 'End time must be after start time';
            return false;
        }
        timeValidation.textContent = '';
        return true;
    };

    // Auto-update Duration
    const updateDuration = () => {
        if (validateTimes()) {
            const start = new Date(document.getElementById('start_time').value);
            const end = new Date(document.getElementById('end_time').value);
            const duration = Math.round((end - start) / (1000 * 60));
            document.getElementById('duration').value = duration;
        }
    };

    // Event Listeners
    document.getElementById('start_time').addEventListener('change', () => {
        validateTimes();
        updateDuration();
    });

    document.getElementById('end_time').addEventListener('change', () => {
        validateTimes();
        updateDuration();
    });

    // Form Validation
    document.getElementById('eventForm').addEventListener('submit', function(e) {
        if (!validateTimes()) {
            e.preventDefault();
            timeValidation.textContent = 'Please fix scheduling errors before submitting';
        }
    });

    // Real-time Input Validation
    const inputs = document.querySelectorAll('input[required], textarea[required]');
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            if (this.checkValidity()) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
            }
        });
    });
});
</script>
@endpush

<style>
.rounded-4 { border-radius: 1rem !important; }
.form-floating label { padding-left: 2.5rem; }
.form-floating .fas { 
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
}
.avatar img { object-fit: cover; }
#timeValidation { min-height: 24px; }
</style>
@endsection