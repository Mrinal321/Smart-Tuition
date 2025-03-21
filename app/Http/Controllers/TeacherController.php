<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class TeacherController extends Controller
{
    public function index(Request $request){
        // Get filter inputs
        $university = $request->input('university_name');
        $department = $request->input('department_name');

        $query = Teacher::select(
            '*',
            DB::raw('ROUND(total_star / NULLIF(star_count, 0), 1) as average_star')
        );

        // Apply filters
        if ($university) {
            $query->where('university_name', $university);
        }

        if ($department) {
            $query->where('department_name', $department);
        }

        // Fetch filtered and sorted teachers
        $teachers = $query->orderBy('average_star', 'desc')->get();

        // Pass distinct universities and departments for filtering options
        $universities = Teacher::select('university_name')->distinct()->pluck('university_name');
        $departments = Teacher::select('department_name')->distinct()->pluck('department_name');

        return view('teacher.index', compact('teachers', 'universities', 'departments', 'university', 'department'));
    }

    // public function rate(Request $request, $id){
    //     // Ensure the user is logged in
    //     if (!auth()->check()) {
    //         return redirect()->route('login')->with('error', 'You must be logged in to rate a teacher.');
    //     }

    //     // Validate the rating
    //     $request->validate([
    //         'star' => 'required|integer|min:1|max:5',
    //     ]);

    //     $teacher = Teacher::findOrFail($id);
    //     $user = auth()->user();

    //     // Check if the user has already rated this teacher
    //     if ($teacher->voters()->where('user_id', $user->id)->exists()) {
    //         return redirect()->back()->with('error', 'You have already rated this teacher.');
    //     }

    //     // Add the rating to the pivot table
    //     $teacher->voters()->attach($user->id, ['star' => $request->star]);

    //     // Update the teacher's total_star and count
    //     $teacher->total_star += $request->star;
    //     $teacher->count += 1;
    //     $teacher->save();

    //     return redirect()->back()->with('success', 'Thank you for your rating!');
    // }

}
