<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ClassRoom; // Correct Model Name
use App\Models\Group;

class DashboardController extends Controller
{
    public function student()
    {
        $user = Auth::user();
        
        // Fetch classes the student has joined
        $classes = $user->classes ?? []; 

        // Fetch groups the student belongs to
        $groups = $user->groups ?? [];

        return view('student.dashboard', compact('classes', 'groups', 'user'));
    }

    public function lecturer()
    {
        $user = Auth::user();

        // Fetch classes the lecturer teaches with student count
        $classes = ClassRoom::where('lecture_id', $user->id)
                            ->withCount('students')
                            ->orderBy('id', 'desc')
                            ->get();

        // For lecturers, maybe show all groups in their classes?
        // Or just show recent activity? For now, let's pass empty groups or all groups in their classes.
        $groups = Group::whereIn('class_id', $classes->pluck('id'))->latest()->take(5)->get();

        return view('lecturer.dashboard', compact('classes', 'groups', 'user'));
    }
}
