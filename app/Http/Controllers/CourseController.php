<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Teacher;

class CourseController extends Controller
{
    public function index(){
        $courses = Course::all(); // Fetch all courses
        $teachers = Teacher::all();
        return view('courses.index', compact('courses', 'teachers'));
    }
    public function show(Course $course){
        return view('courses.show', compact('course'));
    }
    public function create($teacher_id){
        $teacher = Teacher::findOrFail($teacher_id);
        return view('courses.create', compact('teacher'));
    }

    public function store(Request $request){
        $request->validate([
            'course_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'demo_class0' => 'nullable|url',
            'demo_class1' => 'nullable|url',
            'demo_class2' => 'nullable|url',
            'resources_link' => 'nullable|url',
            'teacher_id' => 'required|exists:teachers,id', // Ensure the teacher exists
        ]);

        // Create the course
        $course = Course::create($request->all());

        // Redirect to the courses index page for the teacher
        return redirect()->route('courses.index')->with('success', 'Course created successfully!');
    }
    public function edit(Request $request, $id)
    {
        $course = Course::findOrFail($id);
        return view('courses.edit', compact('course'));
    }
    public function update(Request $request, Course $course)
    {
        $request->validate([
            'course_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'demo_class0' => 'nullable|url',
            'demo_class1' => 'nullable|url',
            'demo_class2' => 'nullable|url',
            'resources_link' => 'nullable|url',
        ]);

        $course->update($request->all());

        return redirect()->route('courses.index')->with('success', 'Course updated successfully!');
    }
    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->route('teachers.courses.index', $course->teacher_id)->with('success', 'Course deleted successfully!');
    }
}
