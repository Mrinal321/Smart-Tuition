@extends('layout', ['title'=> 'Create Course'])

@section('page-content')

<div class="container d-flex justify-content-center">
    <div class="col-md-6 mt-5">
        <div class="card shadow-lg p-4 rounded">
            <h2 class="text-center mb-4">Create Course</h2>

            <form action="{{ route('courses.store') }}" method="POST">
                @csrf

                {{-- Selected Teacher id --}}
                <input type="hidden" name="teacher_id" value="{{ $teacher->id }}">

                <!-- Course Name -->
                <div class="form-group mb-3">
                    <label><i class="bi bi-book"></i> Course Name</label>
                    <input type="text" name="course_name" class="form-control" placeholder="Enter course name" required>
                </div>

                <!-- Description -->
                <div class="form-group mb-3">
                    <label><i class="bi bi-card-text"></i> Description</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Course description"></textarea>
                </div>

                <!-- Demo Classes -->
                <div class="form-group mb-3">
                    <label><i class="bi bi-play-circle"></i> Demo Classes (URLs)</label>
                    <input type="url" name="demo_class0" class="form-control mb-2" placeholder="Demo Class 1 URL">
                    <input type="url" name="demo_class1" class="form-control mb-2" placeholder="Demo Class 2 URL">
                    <input type="url" name="demo_class2" class="form-control" placeholder="Demo Class 3 URL">
                </div>

                <!-- Resources Link -->
                <div class="form-group mb-3">
                    <label><i class="bi bi-link-45deg"></i> Resources Link</label>
                    <input type="url" name="resources_link" class="form-control" placeholder="Enter resources URL">
                </div>

                <!-- Pricing Section -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label><i class="bi bi-tag"></i> Original Fee ($)</label>
                        <input type="number" step="0.01" name="original_fee" class="form-control" placeholder="100.00" required>
                    </div>
                    <div class="col-md-6">
                        <label><i class="bi bi-percent"></i> Discount (%)</label>
                        <input type="number" step="0.01" name="discount" class="form-control" placeholder="0.00" min="0" max="100">
                    </div>
                </div>

                <!-- Auto-calculated Updated Fee -->
                <div class="form-group mb-3 bg-light p-3 rounded">
                    <label><i class="bi bi-calculator"></i> Updated Fee</label>
                    <div class="d-flex align-items-center">
                        <span class="me-2">$</span>
                        <span id="updatedFeeDisplay" class="fw-bold">0.00</span>
                        <input type="hidden" name="updated_fee" id="updatedFee">
                    </div>
                    <small class="text-muted">Calculated automatically</small>
                </div>

                <!-- Form Actions -->
                <div class="text-center mt-4">
                    <a href="{{ route('courses.index') }}" class="btn btn-danger">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Auto-calculate updated fee -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const originalFee = document.querySelector('input[name="original_fee"]');
    const discount = document.querySelector('input[name="discount"]');
    const updatedFeeDisplay = document.getElementById('updatedFeeDisplay');
    const updatedFeeInput = document.getElementById('updatedFee');

    function calculateFee() {
        const price = parseFloat(originalFee.value) || 0;
        const disc = parseFloat(discount.value) || 0;
        const updated = price - (price * disc / 100);
        
        updatedFeeDisplay.textContent = updated.toFixed(2);
        updatedFeeInput.value = updated.toFixed(2);
    }

    originalFee.addEventListener('input', calculateFee);
    discount.addEventListener('input', calculateFee);
    
    // Initial calculation
    calculateFee();
});
</script>

@endsection