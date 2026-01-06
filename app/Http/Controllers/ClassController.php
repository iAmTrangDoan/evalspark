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
        $baseQuery = $user->role === 'lecturer' ? $user->teachingClasses() : $user->classes();

        // Check if viewing archived
        $isArchived = $request->has('archived') && $request->archived == 'true';
        $status = $isArchived ? 'inactive' : 'active';
        
        // Base Status Filter
        $query = $baseQuery->where('status', $status);

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

        // Get all unique semesters for the dropdown (from ALL classes, not just active/filtered, so user can see options)
        $allClasses = $user->role === 'lecturer' ? $user->teachingClasses() : $user->classes();
        $semesters = $allClasses->pluck('semester')->unique()->values();

        if ($user->role === 'lecturer') {
            $classes = $query->withCount(['students', 'groups'])->get();
            return view('classes.lecturer_index', compact('classes', 'semesters', 'isArchived'));
        } else {
            // Students only see active classes generally, unless we want them to see history.
            // For now keeping student view same but respecting global status filter if we applied it.
            // But usually students don't archive classes, they might just leave. 
            // We'll filter active for students by default in query above.
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

        $joinCode = strtoupper(Str::random(6));

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
    public function show(Request $request, ClassRoom $class)
    {
        // Check access
        $user = Auth::user();
        $isMember = $user->classes->contains($class->id);
        $isLecturer = $class->lecture_id === $user->id;

        if (!$isMember && !$isLecturer) {
            abort(403, 'You are not a member of this class.');
        }

        $sort = $request->get('sort', 'newest');

        $class->load(['groups' => function($query) use ($sort) {
            // Eager load relationships needed for the card view
            $query->with(['leader', 'members']); 

            switch ($sort) {
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'oldest':
                    $query->oldest();
                    break;
                case 'newest':
                default:
                    $query->latest();
                    break;
            }
        }, 'students']);

        $classes = [];
        if ($isLecturer) {
             $classes = $user->teachingClasses()->where('status', 'active')->get(); 
        } else {
             $classes = $user->classes;
        }

        return view('classes.show', compact('class', 'isLecturer', 'classes', 'sort'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ClassRoom $class)
    {
        if ($class->lecture_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }
        return view('classes.edit', compact('class'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ClassRoom $class)
    {
        if ($class->lecture_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'join_code' => 'required|string|max:50|unique:classes,join_code,' . $class->id,
            'semester' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $class->update([
            'name' => $request->name,
            'join_code' => $request->join_code,
            'semester' => $request->semester,
            'description' => $request->description,
        ]);

        return redirect()->route('classes.index')->with('success', 'Class updated successfully!');
    }

    /**
     * Archive the class (Soft Delete behavior via status).
     */
    public function destroy(ClassRoom $class)
    {
         if ($class->lecture_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        // Toggle status or just archive set to inactive?
        // User asked "change delete to save class button... from active to inactive"
        // I will implement toggle for flexibility if they click it in archive view to restore.
        
        $newStatus = $class->status === 'active' ? 'inactive' : 'active';
        $message = $newStatus === 'inactive' ? 'Class archived successfully.' : 'Class restored successfully.';

        $class->update(['status' => $newStatus]);

        return redirect()->route('classes.index')->with('success', $message);
    }

    public function join(Request $request)
    {
        $request->validate([
            'join_code' => 'required|string|exists:classes,join_code',
        ]);

        $class = ClassRoom::where('join_code', $request->join_code)->firstOrFail();
        
        if ($class->status !== 'active') {
             return back()->with('error', 'This class is not active.');
        }

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
