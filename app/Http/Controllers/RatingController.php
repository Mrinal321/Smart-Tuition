<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function rateTeacher(Request $request, Teacher $teacher)
    {
        // Validate the rating input
        $request->validate([
            'rating' => 'required|integer|between:1,5',
        ]);

        // Check if the user has already rated this teacher
        $existingRating = Rating::where('user_id', Auth::id())
            ->where('teacher_id', $teacher->id)
            ->first();

        if ($existingRating) {
            return redirect()->back()->with('error', 'You have already rated this teacher.');
        }

        // Create a new rating
        Rating::create([
            'user_id' => Auth::id(),
            'teacher_id' => $teacher->id,
            'rating' => $request->rating,
        ]);

        // Update the teacher's total_star and star_count
        $teacher->total_star += $request->rating;
        $teacher->star_count += 1;
        $teacher->save();

        return redirect()->back()->with('success', 'Thank you for rating this teacher!');
    }
}