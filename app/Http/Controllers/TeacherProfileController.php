<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class TeacherProfileController extends Controller
{
    public function create(){
        return view('teacher.create');
    }

    public function store(Request $request){
        $existingTeacher = Teacher::where('user_teacher_id', auth()->id())->first();

        if ($existingTeacher) {
            return redirect()->back()->with('error', 'You can only create one teacher profile.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'university_name' => 'required|string|max:255',
            'department_name' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers,email',
            'phone_number' => 'required|string|unique:teachers,phone_number',
            'profile_picture' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'university_id_image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);
    
        $teacher = new Teacher();
        $teacher->name = $request->input('name');
        $teacher->university_name = $request->input('university_name');
        $teacher->department_name = $request->input('department_name');
        $teacher->email = $request->input('email');
        $teacher->phone_number = $request->input('phone_number');
        // $teacher->social_media_link = $request->input('social_media_link');
        $teacher->total_star = 0;
        $teacher->star_count = 0;
    
        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move('uploads/teacherprofile/', $filename);
            $teacher->profile_picture = $filename; // Correct field name
        }
    
        // Handle university ID image upload
        if ($request->hasFile('university_id_image')) {
            $file = $request->file('university_id_image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move('uploads/universityid/', $filename);
            $teacher->university_id_image = $filename;
        }
        
        $teacher->user_teacher_id = auth()->id();
        $teacher->save();
    
        return redirect()->back()->with('status', 'Teacher profile added successfully!');
    }
    

    public function profile($id){
        $item = Teacher::find($id);
        $teachers = Teacher::all();
        return view('teacher.profile', compact('item', 'teachers'));
    }

    public function edit(Request $request, $id){
        $teacher = Teacher::find($id);
        return view('teacher.edit', compact('teacher'));
    }

    public function update(Request $request){
        $teacher = Teacher::findOrFail($request->id); // Find or return 404 if not found

        $request->validate([
            'name' => 'nullable|string|max:255',
            'university_name' => 'nullable|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $data = $request->only(['name', 'university_name']); // Get only the required fields

        // Handle profile picture update
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move('uploads/teacherprofile/', $filename);
            $data['profile_picture'] = $filename;
        }

        $teacher->update($data); // Update only specified fields

        return redirect()->route('teacher.index');
    }

}
