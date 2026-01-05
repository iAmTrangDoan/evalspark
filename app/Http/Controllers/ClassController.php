<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = $user->role === 'lecturer' ? $user->teachingClasses() : $user->classes();

        // Search
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('lecturer', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by Semester
        if ($request->has('semester') && $request->semester != 'All Semesters') {
            $query->where('semester', $request->semester);
        }

        // Sort
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'alphabetical':
                $query->orderBy('name');
                break;
            case 'academic_year':
                 $query->orderBy('semester');
                 break;
            case 'newest':
            default:
                $query->latest();
                break;
        }

        // Get all unique semesters for the dropdown (before filtering)
        $semesters = $user->role === 'lecturer' 
            ? $user->teachingClasses()->pluck('semester')->unique()->values() 
            : $user->classes()->pluck('semester')->unique()->values();

        if ($user->role === 'lecturer') {
            $classes = $query->withCount(['students', 'groups'])->get();
            return view('classes.lecturer_index', compact('classes', 'semesters'));
        } else {
            $classes = $query->get();
            return view('classes.index', compact('classes', 'semesters'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('classes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'semester' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $joinCode = strtoupper(Str::random(6)); // Generate unique code needs check, but random(6) is usually fine for MVP

        ClassRoom::create([
            'lecture_id' => Auth::id(),
            'name' => $request->name,
            'semester' => $request->semester,
            'description' => $request->description,
            'join_code' => $joinCode,
            'status' => 'active',
        ]);

        return redirect()->route('classes.index')->with('success', 'Class created successfully! Join Code: ' . $joinCode);
    }

    /**
     * Display the specified resource.
     */
    public function show(ClassRoom $class)
    {
        // Check access
        $user = Auth::user();
        $isMember = $user->classes->contains($class->id);
        $isLecturer = $class->lecture_id === $user->id;

        if (!$isMember && !$isLecturer) {
            abort(403, 'You are not a member of this class.');
        }

        $class->load(['groups', 'students']);

        $classes = [];
        if ($isLecturer) {
             $classes = $user->teachingClasses;
        } else {
             $classes = $user->classes;
        }

        return view('classes.show', compact('class', 'isLecturer', 'classes'));
    }

    public function join(Request $request)
    {
        $request->validate([
            'join_code' => 'required|string|exists:classes,join_code',
        ]);

        $class = ClassRoom::where('join_code', $request->join_code)->firstOrFail();
        
        // Prevent lecturer or existing member
        if ($class->lecture_id === Auth::id()) {
            return back()->with('error', 'You are the lecturer of this class.');
        }

        if ($class->students->contains(Auth::id())) {
             return back()->with('error', 'You are already in this class.');
        }

        // Attach
        $class->students()->attach(Auth::id());

        return redirect()->route('classes.show', $class)->with('success', 'Joined class successfully!');
    }
}
