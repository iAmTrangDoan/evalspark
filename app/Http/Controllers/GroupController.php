<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Search & Sort parameters
        $search = $request->get('search');
        $sort = $request->get('sort', 'newest');

        // Check if lecturer
        if ($user->role === 'lecturer') {
            $classes = $user->teachingClasses()->with(['groups' => function($query) use ($search, $sort) {
                
                $query->with(['members', 'leader']);
                
                // Search in groups
                if ($search) {
                    $query->where(function($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                          ->orWhereHas('members', function($q) use ($search) {
                              $q->where('name', 'like', "%{$search}%");
                          });
                    });
                }

                // Sort in groups
                switch ($sort) {
                    case 'alphabetical':
                        $query->orderBy('name');
                        break;
                    case 'most_members':
                        $query->withCount('members')->orderByDesc('members_count');
                        break;
                    case 'newest':
                    default:
                        $query->latest();
                        break;
                }

            }])->get();
            
            return view('groups.lecturer_index', compact('classes'));
        }
        
        // Student View
        $groupQuery = $user->groups()->with(['classRoom', 'members', 'leader']);

        if ($search) {
            $groupQuery->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('members', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        switch ($sort) {
            case 'alphabetical':
                $groupQuery->orderBy('name');
                break;
            case 'most_members':
                $groupQuery->withCount('members')->orderByDesc('members_count');
                break;
            case 'newest':
            default:
                $groupQuery->latest();
                break;
        }

        $groups = $groupQuery->get()->groupBy(function($group) {
             return $group->classRoom ? $group->classRoom->name : 'General';
        });
        $classes = $user->classes; 
        return view('groups.index', compact('groups', 'classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'name' => 'required|string|max:255',
            'is_public' => 'boolean',
        ]);

        // Logic: Check if user is student in class
        // For simplicity assuming valid class member via earlier middleware/checks or implicit

        $group = Group::create([
            'class_id' => $request->class_id,
            'leader_id' => Auth::id(),
            'name' => $request->name,
            'is_public' => $request->boolean('is_public'),
            'status' => 'active',
        ]);

        // Add creator as leader member
        $group->members()->attach(Auth::id(), ['role' => 'leader']);

        return redirect()->route('classes.show', $request->class_id)->with('success', 'Group created successfully!');
    }

    public function create()
    {
        $classes = Auth::user()->classes; // Or whatever relation gets enrolled classes
        return view('groups.create', compact('classes'));
    }

    public function show(Group $group)
    {
        // Permission Check
        $user = Auth::user();
        
        // 1. HIGHEST PERMISSION: Instructor
        $isLecturer = $group->classRoom->lecture_id === $user->id;
        
        // 2. MEMBER PERMISSION
        $isMember = $group->members->contains($user->id);
        
        // 3. PUBLIC PERMISSION
        $isPublic = $group->is_public;

        if ($isLecturer) {
            // Allowed: Access + Grading
        } elseif ($isMember) {
            // Allowed: Access (No grading)
        } elseif ($isPublic) {
            // Allowed: Access (No grading) - Anyone can view
        } else {
            // Private and not a member/lecturer
             return redirect()->route('classes.show', $group->class_id)->with('error', 'This is a private group. You do not have permission to view it.');
        }

        // Fetch group with relationships
        $group->load(['classRoom.lecturer', 'members', 'lists.cards.checklists.items', 'lists.cards.assignedUser', 'leader', 'scores']);

        // Calculate Progress based on is_completed attribute
        $allCards = $group->lists->flatMap->cards;
        $totalCards = $allCards->count();
        $completedCards = $allCards->where('is_completed', true)->count();

        $completionPercentage = $totalCards > 0 ? round(($completedCards / $totalCards) * 100) : 0;

        // Get eligible students (in class but not in group)
        $classStudents = $group->classRoom->students;
        $existingMemberIds = $group->members->pluck('id')->toArray();
        $eligibleStudents = $classStudents->whereNotIn('id', $existingMemberIds);

        // Get user's score if they are a member
        $userScore = null;
        if ($isMember) {
            $scoreRecord = $group->scores->firstWhere('user_id', Auth::id());
            $userScore = $scoreRecord ? $scoreRecord->score : null;
        }

        return view('groups.show', compact('group', 'isLecturer', 'isMember', 'completionPercentage', 'totalCards', 'completedCards', 'eligibleStudents', 'userScore'));
    }
    public function storeMember(Request $request)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $group = Group::findOrFail($request->group_id);
        
        // Check if already a member
        if($group->members->contains($request->user_id)) {
            return back()->with('error', 'User is already a member.');
        }

        $group->members()->attach($request->user_id, ['role' => 'member']);

        return back()->with('success', 'Member added successfully.');
    }
    public function transferLeadership(Request $request, Group $group)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $newLeaderId = $request->user_id;
        $currentLeaderId = Auth::id();

        // 1. Verify current user is the leader
        if ($group->leader_id !== $currentLeaderId) {
             abort(403, 'Only the leader can transfer leadership.');
        }

        // 2. Verify target user is in the group
        // using query to ensure we check against DB even if relation not loaded or stale
        if (!$group->members()->where('user_id', $newLeaderId)->exists()) {
            return back()->with('error', 'The selected user is not a member of this group.');
        }

        // 3. Update Group Leader ID
        $group->update(['leader_id' => $newLeaderId]);

        // 4. Update Pivot Roles
        // Set old leader to member
        $group->members()->updateExistingPivot($currentLeaderId, ['role' => 'member']);
        
        // Set new leader to leader
        $group->members()->updateExistingPivot($newLeaderId, ['role' => 'leader']);

        return back()->with('success', 'Leadership transferred successfully.');
    }
    public function searchCandidates(Request $request, Group $group)
    {
        $search = $request->query('q');

        if (!$search || strlen($search) < 1) {
            return response()->json([]);
        }

        // Get IDs of current members to exclude
        $memberIds = $group->members()->pluck('users.id')->toArray();

        // Query students in the class
        $students = $group->classRoom->students()
            ->whereNotIn('users.id', $memberIds)
            ->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            })
            ->take(3) // Suggest about 3
            ->get(['id', 'name', 'email', 'avatar']); // Select necessary fields

        // Append avatar_url accessor
        $students->each(function($student) {
            $student->avatar_url = $student->avatar_url ?? "https://ui-avatars.com/api/?name=".urlencode($student->name)."&background=random";
        });

        return response()->json($students);
    }
    public function update(Request $request, Group $group)
    {
        // Permission Check: Leader or Lecturer
        $user = Auth::user();
        $isLeader = $group->leader_id === $user->id;
        $isLecturer = $group->classRoom->lecture_id === $user->id;

        if (!$isLeader && !$isLecturer) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_public' => 'boolean',
        ]);

        $group->update([
            'name' => $request->name,
            'description' => $request->description,
            'is_public' => $request->boolean('is_public'),
        ]);

        return back()->with('success', 'Group updated successfully.');
    }

    public function destroy(Group $group)
    {
        // Permission Check: Leader or Lecturer
        $user = Auth::user();
        $isLeader = $group->leader_id === $user->id;
        $isLecturer = $group->classRoom->lecture_id === $user->id;

        if (!$isLeader && !$isLecturer) {
            abort(403, 'Unauthorized action.');
        }

        $group->delete();

        return back()->with('success', 'Group deleted successfully.');
    }
}
