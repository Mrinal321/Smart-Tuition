<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function rateTeacher(Request $request, Teacher $teacher) {
        $request->validate([
            'rating' => 'required|integer|between:1,5',
        ]);

        $userId = Auth::id();

        // Check for duplicate rating
        $existingRating = Rating::where('user_id', $userId)
            ->where('teacher_id', $teacher->id)
            ->first();

        if ($existingRating) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already rated this teacher.'
                ], 400);
            }
            return redirect()->back()->with('error', 'You have already rated this teacher.');
        }

        // Save new rating
        $rating = Rating::create([
            'user_id' => $userId,
            'teacher_id' => $teacher->id,
            'rating' => $request->rating,
        ]);

        // Update stats
        $teacher->increment('total_star', $request->rating);
        $teacher->increment('star_count');

        // AJAX response
        if ($request->ajax()) {
            $average = $teacher->star_count > 0 
                ? number_format($teacher->total_star / $teacher->star_count, 1) 
                : 0;

            return response()->json([
                'success' => true,
                'rating' => $rating->rating,
                'average_rating' => $average,
                'total_ratings' => $teacher->star_count,
            ]);
        }

        return redirect()->back()->with('success', 'Thank you for rating this teacher!');
    }
}
