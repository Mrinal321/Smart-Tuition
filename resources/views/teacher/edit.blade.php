@extends('layout', ['title'=> 'Update Teacher'])
@section('page-content')

<div class="container mt-5">
    <h2 class="text-center mb-4">Update Teacher Profile</h2>

    <form action="{{ route('teacher.update', $teacher->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Name Field -->
        <div class="form-group mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $teacher->name }}" required>
        </div>

        <!-- University Field -->
        <div class="form-group mb-3">
            <label for="university" class="form-label">University</label>
            <input type="text" name="university" id="university" class="form-control" value="{{ $teacher->university_name }}" required>
        </div>

        <!-- Department Field -->
        <div class="form-group mb-3">
            <label for="department" class="form-label">Department</label>
            <input type="text" name="department" id="department" class="form-control" value="{{ $teacher->department_name }}" required>
        </div>

        <!-- Social Media Link Field -->
        <div class="form-group mb-3">
            <label for="social_media_link" class="form-label">Social Media Link</label>
            <input type="url" name="social_media_link" id="social_media_link" class="form-control" value="{{ $teacher->social_media_link }}" placeholder="https://example.com">
        </div>

        <!-- Profile Picture Field -->
        <div class="form-group mb-3">
            <label for="profile_picture" class="form-label">Profile Image</label>
            <input type="file" name="profile_picture" id="profile_picture" class="form-control">
            @if($teacher->profile_picture)
                <div class="mt-2">
                    <img src="{{ asset('uploads/teacherprofile/' . $teacher->profile_picture) }}" width="70px" height="70px" alt="Profile Picture" class="img-thumbnail">
                </div>
            @endif
        </div>

        <!-- Submit Button -->
        <div class="form-group mb-3">
            <button type="submit" class="btn btn-primary w-100">Update Profile</button>
        </div>
    </form>
</div>

@endsection