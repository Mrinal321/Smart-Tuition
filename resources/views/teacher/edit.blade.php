@extends('layout', ['title'=> 'Update Teacher'])
@section('page-content')

<div class="container mt-5">
    <div class="card shadow-lg rounded-4">
        <div class="card-header bg-primary text-white py-3 rounded-top-4">
            <h2 class="h4 mb-0 text-center"><i class="fas fa-chalkboard-teacher me-2"></i>Update Teacher Profile</h2>
        </div>
        
        <div class="card-body p-4">
            <form action="{{ route('teacher.update', $teacher->id) }}" method="POST" enctype="multipart/form-data" id="updateForm">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <!-- Name Field -->
                        <div class="form-floating">
                            <input type="text" name="name" id="name" class="form-control" 
                                   value="{{ $teacher->name }}" placeholder="Name" required>
                            <label for="name"><i class="fas fa-user me-2"></i>Full Name</label>
                            <div class="invalid-feedback" id="nameFeedback"></div>
                        </div>

                        <!-- University Field -->
                        <div class="form-floating mt-3">
                            <input type="text" name="university" id="university" class="form-control"
                                   value="{{ $teacher->university_name }}" placeholder="University" required>
                            <label for="university"><i class="fas fa-university me-2"></i>University</label>
                        </div>

                        <!-- Department Field -->
                        <div class="form-floating mt-3">
                            <input type="text" name="department" id="department" class="form-control"
                                   value="{{ $teacher->department_name }}" placeholder="Department" required>
                            <label for="department"><i class="fas fa-building me-2"></i>Department</label>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6">
                        <!-- Social Media Link Field -->
                        <div class="form-floating">
                            <input type="url" name="social_media_link" id="social_media_link" class="form-control"
                                   value="{{ $teacher->social_media_link }}" placeholder="Social Media Link">
                            <label for="social_media_link"><i class="fas fa-link me-2"></i>Social Media Link</label>
                        </div>

                        <!-- Profile Picture Field -->
                        <div class="mt-4">
                            <div class="card border-dashed">
                                <div class="card-body text-center">
                                    <div class="avatar-upload">
                                        <div class="avatar-edit">
                                            <input type="file" name="profile_picture" id="profile_picture" 
                                                   accept=".png, .jpg, .jpeg" class="form-control d-none">
                                            <label for="profile_picture" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-camera me-2"></i>Change Photo
                                            </label>
                                        </div>
                                        <div class="avatar-preview mt-3">
                                            <div id="imagePreview" 
                                                 style="background-image: url('{{ asset('uploads/teacherprofile/' . $teacher->profile_picture) }}');">
                                            </div>
                                        </div>
                                    </div>
                                    <small class="text-muted d-block mt-2">Allowed JPG, PNG. Max size 2MB</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-primary btn-lg rounded-2 fw-bold py-3">
                        <i class="fas fa-save me-2"></i>Update Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Image Preview Handler
    function readURL(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('imagePreview').style.backgroundImage = 'url(' + e.target.result + ')';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    document.getElementById('profile_picture').addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            if (!file.type.match('image.*')) {
                alert('Please select an image file');
                this.value = '';
                return;
            }
            if (file.size > 2 * 1024 * 1024) {
                alert('File size must be less than 2MB');
                this.value = '';
                return;
            }
            readURL(this);
        }
    });

    // Real-time Validation
    const inputs = document.querySelectorAll('input[required]');
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

    // Name Validation
    const nameInput = document.getElementById('name');
    nameInput.addEventListener('input', function() {
        const nameFeedback = document.getElementById('nameFeedback');
        if (this.value.length < 3) {
            nameFeedback.textContent = 'Name must be at least 3 characters';
        } else {
            nameFeedback.textContent = '';
        }
    });

    // Form Submission Handler
    document.getElementById('updateForm').addEventListener('submit', function(e) {
        if (!this.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        this.classList.add('was-validated');
    });
});
</script>

<style>
.avatar-upload .avatar-preview {
    width: 120px;
    height: 120px;
    margin: 0 auto;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid #e9ecef;
}

#imagePreview {
    width: 100%;
    height: 100%;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}

.border-dashed {
    border: 2px dashed #dee2e6 !important;
}

.form-floating label {
    padding-left: 2.5rem;
}

.form-floating .fas {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
}
</style>

@endsection