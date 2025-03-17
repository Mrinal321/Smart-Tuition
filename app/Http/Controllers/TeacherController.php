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

}
