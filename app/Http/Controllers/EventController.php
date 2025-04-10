<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class EventController extends Controller
{
    public function index(){
        $currentTime = now(); // Get the current time
        $upcoming = Event::where('start_time', '>', $currentTime)
            ->orderBy('start_time', 'asc')->get();

        $running = Event::where('end_time', '>', $currentTime)
            ->where('start_time', '<=', $currentTime)
            ->orderBy('start_time', 'asc')
            ->get();

        $previous = Event::where('end_time', '<', $currentTime)
            ->orderBy('start_time', 'desc')->get();

        $events = Event::all();
        
        $teachers = Teacher::all();

        return view('events.index', compact('upcoming','running', 'previous', 'teachers', 'events'));
    }
    public function create($id){
        // Get the authenticated teacher (assuming teacher is logged in)
        $teacher = Teacher::where('user_teacher_id', auth()->id())->first();
        
        // Or if you're getting teacher by ID from route parameter
        // $teacher = Teacher::find($request->teacher_id);
        if (!$teacher) {
            abort(404, 'Teacher not found');
        }

        return view('events.create', compact('teacher'));
    }

    public function store(Request $request){
        // Validate the request data
        $validated = $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|integer|min:1',
            'start_time' => 'required|date|after_or_equal:now',
            'end_time' => 'required|date|after:start_time',
        ]);

        // Convert times from Dhaka timezone to UTC before saving
        $start_time = Carbon::parse($validated['start_time'], 'Asia/Dhaka')->utc();
        $end_time = Carbon::parse($validated['end_time'], 'Asia/Dhaka')->utc();

        // Create the event
        $event = Event::create([
            'teacher_id' => $validated['teacher_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'duration' => $validated['duration'],
            'start_time' => $start_time,
            'end_time' => $end_time,
        ]);

        return redirect()->route('events.index')
            ->with('success', 'Class schedule created successfully!');
    }

    public function show(Event $event)
    {
        return view('events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        $teachers = Teacher::all();
        return view('events.edit', compact('event', 'teachers'));
    }

    public function update(Request $request, Event $event)
    {
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'duration' => 'required|integer|min:1',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        // Convert to UTC before saving
        $start_time = Carbon::parse($request->start_time, 'Asia/Dhaka')->utc();
        $end_time = Carbon::parse($request->end_time, 'Asia/Dhaka')->utc();

        $event->update([
            'teacher_id' => $request->teacher_id,
            'title' => $request->title,
            'description' => $request->description,
            'duration' => $request->duration,
            'start_time' => $start_time,
            'end_time' => $end_time,
        ]);

        return redirect()->route('events.index')->with('success', 'Event updated successfully!');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('events.index')->with('success', 'Event deleted successfully!');
    }
}