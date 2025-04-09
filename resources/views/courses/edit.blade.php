@extends('layout', ['title' => 'Edit Course: ' . $course->course_name])

@section('page-content')

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h2 class="h5 mb-0">
                        <i class="bi bi-pencil-square me-2"></i>Edit Course: {{ $course->course_name }}
                    </h2>
                </div>
                
                <div class="card-body p-4">
                    <form action="{{ route('courses.update', $course->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Hidden Course ID -->
                        <input type="hidden" name="id" value="{{ $course->id }}">

                        <!-- Course Name -->
                        <div class="mb-4">
                            <label for="course_name" class="form-label fw-bold">Course Name</label>
                            <input type="text" class="form-control @error('course_name') is-invalid @enderror" 
                                   id="course_name" name="course_name" 
                                   value="{{ old('course_name', $course->course_name) }}" required>
                            @error('course_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="form-label fw-bold">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4">{{ old('description', $course->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Demo Classes Section -->
                        <div class="mb-4">
                            <h5 class="fw-bold mb-3 text-primary">
                                <i class="bi bi-play-circle me-2"></i>Demo Classes
                            </h5>
                            
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="demo_class0" class="form-label">Demo 1</label>
                                    <input type="url" class="form-control" id="demo_class0" name="demo_class0"
                                           value="{{ old('demo_class0', $course->demo_class0) }}" placeholder="https://example.com/demo1">
                                </div>
                                <div class="col-md-4">
                                    <label for="demo_class1" class="form-label">Demo 2</label>
                                    <input type="url" class="form-control" id="demo_class1" name="demo_class1"
                                           value="{{ old('demo_class1', $course->demo_class1) }}" placeholder="https://example.com/demo2">
                                </div>
                                <div class="col-md-4">
                                    <label for="demo_class2" class="form-label">Demo 3</label>
                                    <input type="url" class="form-control" id="demo_class2" name="demo_class2"
                                           value="{{ old('demo_class2', $course->demo_class2) }}" placeholder="https://example.com/demo3">
                                </div>
                            </div>
                        </div>

                        <!-- Resources Link -->
                        <div class="mb-4">
                            <label for="resources_link" class="form-label fw-bold">Resources Link</label>
                            <input type="url" class="form-control @error('resources_link') is-invalid @enderror" 
                                   id="resources_link" name="resources_link" 
                                   value="{{ old('resources_link', $course->resources_link) }}" placeholder="https://example.com/resources">
                            @error('resources_link')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Pricing Section -->
                        <div class="mb-4">
                            <h5 class="fw-bold mb-3 text-primary">
                                <i class="bi bi-currency-dollar me-2"></i>Pricing
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="original_fee" class="form-label"><i class="bi bi-tag me-1"></i>Original Fee ($)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" step="0.01" class="form-control" id="original_fee" name="original_fee" 
                                            value="{{ old('original_fee', $course->original_fee) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="discount" class="form-label"><i class="bi bi-percent me-1"></i>Discount (%)</label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" min="0" max="100" class="form-control" id="discount" name="discount"
                                            value="{{ old('discount', $course->discount) }}">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Auto-calculated Updated Fee -->
                            <div class="bg-light p-3 rounded mt-3">
                                <label class="d-flex align-items-center">
                                    <i class="bi bi-calculator me-2"></i>Updated Fee
                                </label>
                                <div class="d-flex align-items-center">
                                    <span class="me-2">$</span>
                                    <span id="updatedFeeDisplay" class="fw-bold">
                                        {{ number_format($course->updated_fee, 2) }}
                                    </span>
                                    <input type="hidden" name="updated_fee" id="updatedFee" value="{{ $course->updated_fee }}">
                                </div>
                                <small class="text-muted">Calculated automatically</small>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="{{ route('courses.index') }}" class="btn btn-danger me-md-2">
                                <i class="bi bi-arrow-left me-1"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i> Update Course
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const originalFee = document.getElementById('original_fee');
        const discount = document.getElementById('discount');
        const updatedFeeDisplay = document.getElementById('updatedFeeDisplay');
        const updatedFeeInput = document.getElementById('updatedFee');
    
        function calculateUpdatedFee() {
            const price = parseFloat(originalFee.value) || 0;
            const disc = parseFloat(discount.value) || 0;
            const updated = price - (price * disc / 100);
            
            updatedFeeDisplay.textContent = updated.toFixed(2);
            updatedFeeInput.value = updated.toFixed(2);
        }
    
        originalFee.addEventListener('input', calculateUpdatedFee);
        discount.addEventListener('input', calculateUpdatedFee);
        
        // Initial calculation
        calculateUpdatedFee();
    });
    </script>

<style>
    .card {
        border-radius: 0.5rem;
    }
    .form-control:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25);
    }
</style>

@endsection