@extends('layout', ['title' => 'Edit Course'])
@section('page-content')

<div class="container mt-5">
    <h2 class="text-center mb-4">Edit Course</h2>

    <form action="{{ route('teachers.courses.update', [$course->teacher_id, $course->id]) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Course Name -->
        <div class="form-group mb-3">
            <label for="course_name">Course Name</label>
            <input type="text" name="course_name" id="course_name" class="form-control" value="{{ $course->course_name }}" required>
        </div>

        <!-- Description -->
        <div class="form-group mb-3">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control" rows="3">{{ $course->description }}</textarea>
        </div>

        <!-- Demo Classes -->
        <div class="form-group mb-3">
            <label for="demo_class0">Demo Class 0 Link</label>
            <input type="url" name="demo_class0" id="demo_class0" class="form-control" value="{{ $course->demo_class0 }}">
        </div>
        <div class="form-group mb-3">
            <label for="demo_class1">Demo Class 1 Link</label>
            <input type="url" name="demo_class1" id="demo_class1" class="form-control" value="{{ $course->demo_class1 }}">
        </div>
        <div class="form-group mb-3">
            <label for="demo_class2">Demo Class 2 Link</label>
            <input type="url" name="demo_class2" id="demo_class2" class="form-control" value="{{ $course->demo_class2 }}">
        </div>

        <!-- Resources Link -->
        <div class="form-group mb-3">
            <label for="resources_link">Resources Link</label>
            <input type="url" name="resources_link" id="resources_link" class="form-control" value="{{ $course->resources_link }}">
        </div>

        <!-- Submit Button -->
        <div class="form-group mb-3">
            <button type="submit" class="btn btn-primary w-100">Update Course</button>
        </div>
    </form>
</div>

@endsection