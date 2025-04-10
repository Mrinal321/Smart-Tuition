@extends('layout', ['title'=> 'Home'])

@section('page-content')

<div class="container d-flex justify-content-center">
    <div class="col-md-7 mt-5">
        <div class="card shadow-lg p-4 rounded position-relative animate__animated animate__fadeIn">
            <h2 class="text-center mb-4 text-primary fw-bold"><i class="bi bi-person-lines-fill me-2"></i>Create Teacher</h2>

            <form id="teacherForm" action="{{ route('teacher.store')}}" method="POST" enctype="multipart/form-data" novalidate>
                @csrf

                <div class="form-group mb-3">
                    <label><i class="bi bi-person"></i> Name</label>
                    <input type="text" name="name" class="form-control shadow-sm" placeholder="Enter teacher's name" required>
                </div>

                <div class="form-group mb-3">
                    <label><i class="bi bi-building"></i> University</label>
                    <input type="text" name="university_name" class="form-control shadow-sm" placeholder="Enter university name" required>
                </div>

                <div class="form-group mb-3">
                    <label><i class="bi bi-journal-bookmark"></i> Department</label>
                    <input type="text" name="department_name" class="form-control shadow-sm" placeholder="Enter department name" required>
                </div>

                <div class="form-group mb-3">
                    <label><i class="bi bi-envelope"></i> Email</label>
                    <input type="email" name="email" class="form-control shadow-sm" placeholder="Enter email" required>
                </div>

                <div class="form-group mb-3">
                    <label><i class="bi bi-telephone"></i> Phone Number</label>
                    <input type="text" name="phone_number" class="form-control shadow-sm" placeholder="Enter phone number" required>
                </div>

                <div class="form-group mb-3">
                    <label><i class="bi bi-image"></i> Profile Picture</label>
                    <input type="file" name="profile_picture" class="form-control shadow-sm">
                </div>

                <div class="form-group mb-3">
                    <label><i class="bi bi-card-image"></i> University ID Image</label>
                    <input type="file" name="university_id_image" class="form-control shadow-sm">
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('teacher.index') }}" class="btn btn-outline-danger me-2">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-check-circle"></i> Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Styles and Animations --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet"/>

<style>
    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }
</style>

{{-- JavaScript Enhancements --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('teacherForm');

        // Tooltips on all inputs
        const inputs = form.querySelectorAll('input[type="text"], input[type="email"]');
        inputs.forEach(input => {
            input.setAttribute('data-bs-toggle', 'tooltip');
            input.setAttribute('data-bs-placement', 'top');
            input.setAttribute('title', input.placeholder);
        });
        new bootstrap.Tooltip(document.body, {
            selector: '[data-bs-toggle="tooltip"]'
        });

        // Animate submit button on hover
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.addEventListener('mouseenter', () => {
            submitBtn.classList.add('animate__pulse', 'animate__animated');
        });
        submitBtn.addEventListener('mouseleave', () => {
            submitBtn.classList.remove('animate__pulse', 'animate__animated');
        });

        // Simple client-side validation animation
        form.addEventListener('submit', function (e) {
            if (!form.checkValidity()) {
                e.preventDefault();
                form.classList.add('was-validated');
                form.classList.add('animate__shakeX', 'animate__animated');
                setTimeout(() => {
                    form.classList.remove('animate__shakeX', 'animate__animated');
                }, 1000);
            }
        });
    });
</script>

@endsection
