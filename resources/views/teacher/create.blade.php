@extends('layout', ['title'=> 'Home'])

@section('page-content')

<div class="container d-flex justify-content-center">
    <div class="col-md-6 mt-5">
        <div class="card shadow-lg p-4 rounded">
            <h2 class="text-center mb-4">Create Teacher</h2>

            <form action="{{ route('teacher.store')}}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group mb-3">
                    <label><i class="bi bi-person"></i> Name</label>
                    <input type="text" name="name" class="form-control" placeholder="Enter teacher's name" required>
                </div>

                <div class="form-group mb-3">
                    <label><i class="bi bi-building"></i> University</label>
                    <input type="text" name="university_name" class="form-control" placeholder="Enter university name" required>
                </div>

                <div class="form-group mb-3">
                    <label><i class="bi bi-journal-bookmark"></i> Department</label>
                    <input type="text" name="department_name" class="form-control" placeholder="Enter department name" required>
                </div>

                <div class="form-group mb-3">
                    <label><i class="bi bi-envelope"></i> Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Enter email" required>
                </div>

                <div class="form-group mb-3">
                    <label><i class="bi bi-telephone"></i> Phone Number</label>
                    <input type="text" name="phone_number" class="form-control" placeholder="Enter phone number" required>
                </div>

                <div class="form-group mb-3">
                    <label><i class="bi bi-image"></i> Profile Picture</label>
                    <input type="file" name="profile_picture" class="form-control">
                </div>

                <div class="form-group mb-3">
                    <label><i class="bi bi-card-image"></i> University ID Image</label>
                    <input type="file" name="university_id_image" class="form-control">
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('teacher.index') }}" class="btn btn-danger">
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

@endsection
