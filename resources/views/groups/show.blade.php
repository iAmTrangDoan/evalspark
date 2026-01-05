@php $hideSidebar = true; @endphp
@extends('layouts.student_dashboard')

@section('content')
<style>
    /* Kanban Styles Override/Addition */
    :root {
        --primary: #0079BF;
        --primary-dark: #005a8e;
        --background-light: #F4F5F7;
        --text-main: #172B4D;
        --primary-hover: #006096;
        --bg-light: #f5f7f8;
        --surface-light: #ffffff;
        --input-light: #e6eff4;
    }

    .kanban-column {
        flex: 0 0 320px;
        width: 320px;
        background-color: #ebecf0;
        border-radius: 12px;
        padding: 10px;
        margin-right: 1.5rem;
        display: flex;
        flex-direction: column;
        max-height: calc(100vh - 240px);
    }
    
    .kanban-cards-wrapper {
        flex: 1;
        overflow-y: auto;
        padding-right: 4px; /* Prevent scrollbar overlap */
        min-height: 50px;
    }
    
    /* Ensure board container handles overflow */
    #board-container {
        overflow-x: auto;
        flex-wrap: nowrap;
        padding-bottom: 1rem;
        /* height: calc(100vh - 200px); Optional: fix board height */
    }

    .kanban-card {
        background: white;
        border-radius: 8px;
        padding: 12px;
        margin-bottom: 10px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.12);
        cursor: grab;
        border: 1px solid transparent;
    }

    .kanban-card:active { cursor: grabbing; }
    .kanban-card.dragging { opacity: 0.5; border: 1px dashed var(--primary); }

    .badge-custom {
        font-size: 10px;
        font-weight: 700;
        padding: 4px 8px;
        border-radius: 4px;
        margin-bottom: 8px;
        display: inline-block;
    }

    .avatar-sm {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background-size: cover;
        background-position: center;
    }

    .progress-container {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    /* Drag over effect */
    .kanban-column.drag-over { background-color: #dfe1e6; outline: 2px dashed #4c9aff; }

    /* Priority Colors */
    .priority-high { color: #dc3545; } /* Red */
    .priority-medium { color: #fd7e14; } /* Orange */
    .priority-low { color: #ffc107; } /* Yellow */

    /* Task Detail Modal Styles */
    .task-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 28, 35, 0.6);
        backdrop-filter: blur(4px);
        z-index: 1050;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        display: none; /* Hidden by default */
    }

    .task-modal {
        background: white;
        width: 100%;
        max-width: 1140px;
        height: 90vh; /* Changed from max-height to height to enforce scrolling */
        border-radius: 1rem;
        display: flex;
        flex-direction: column;
        position: relative;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        overflow: hidden;
    }

    /* Custom Scrollbar */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    .modal-body-content {
        overflow-y: auto;
        flex: 1;
    }


    .editable-title {
        font-size: 1.5rem;
        font-weight: 700;
        border: 2px solid transparent;
        border-radius: 0.5rem;
        padding: 0.25rem 0.5rem;
        margin-left: -0.5rem;
        width: 100%;
        transition: all 0.2s;
    }
    .editable-title:focus {
        outline: none;
        background: white;
        border-color: rgba(0, 121, 191, 0.2);
        box-shadow: 0 0 0 4px rgba(0, 121, 191, 0.1);
    }

    .property-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.4rem;
    }

    .property-btn {
        background: var(--input-light);
        border: none;
        border-radius: 0.5rem;
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        font-weight: 500;
        width: 100%;
        text-align: left;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: background 0.2s;
    }
    .property-btn:hover {
        background: #cbd5e1;
    }

    .activity-line {
        position: absolute;
        left: 1.15rem;
        top: 0.5rem;
        bottom: 0.5rem;
        width: 2px;
        background: #e2e8f0;
        z-index: 0;
    }

    .comment-box {
        border-radius: 0 0.75rem 0.75rem 0.75rem;
        background: white;
        border: 1px solid #e2e8f0;
        padding: 0.75rem;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }

    .comment-box-me {
        border-radius: 0.75rem 0 0.75rem 0.75rem;
        background: rgba(0, 121, 191, 0.1);
        border: 1px solid rgba(0, 121, 191, 0.2);
    }

    .subtask-checked {
        text-decoration: line-through;
        color: #94a3b8;
    }

    .btn-save {
        background-color: var(--primary);
        color: white;
        border: none;
        box-shadow: 0 10px 15px -3px rgba(0, 121, 191, 0.3);
    }
    .btn-save:hover {
        background-color: var(--primary-hover);
        color: white;
    }
    
    .form-check-input:checked {
        background-color: #22c55e;
        border-color: #22c55e;
    }

    /* Add Member Modal Custom Styles */
    #addMemberModal .modal-content {
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
    #addMemberModal .modal-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #f1f5f9;
    }
    #addMemberModal .modal-body {
        padding: 1rem 1.5rem;
        max-height: 65vh;
        overflow-y: auto;
    }
    #addMemberModal .search-wrapper { position: relative; margin-bottom: 1.5rem; }
    #addMemberModal .search-wrapper .material-symbols-outlined {
        position: absolute; left: 0.875rem; top: 50%; transform: translateY(-50%); color: #64748b; pointer-events: none;
    }
    #addMemberModal .search-input {
        width: 100%; height: 3rem; padding-left: 2.75rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; outline: none; transition: 0.2s;
    }
    #addMemberModal .search-input:focus {
        border-color: #0078bd; box-shadow: 0 0 0 4px rgba(0, 120, 189, 0.1); background: white;
    }
    #addMemberModal .section-title {
        font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; margin-bottom: 0.75rem;
    }
    #addMemberModal .suggestion-item {
        padding: 0.75rem; border-radius: 10px; display: flex; align-items: center; justify-content: space-between; cursor: pointer; transition: 0.2s; margin-bottom: 0.5rem; border: 1px solid transparent;
    }
    #addMemberModal .suggestion-item:hover { background-color: #f8fafc; }
    #addMemberModal .suggestion-item.selected {
        background-color: rgba(0, 120, 189, 0.05); border-color: rgba(0, 120, 189, 0.2);
    }
    #addMemberModal .user-avatar {
        width: 40px; height: 40px; border-radius: 50%; background-size: cover; background-position: center; flex-shrink: 0;
    }
    #addMemberModal .check-badge {
        width: 24px; height: 24px; background: #0078bd; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center;
    }
    #addMemberModal .member-item {
        padding: 0.5rem 0.75rem; border-radius: 10px; display: flex; align-items: center; justify-content: space-between; transition: 0.2s;
    }
    #addMemberModal .member-item:hover { background-color: #f8fafc; }
    #addMemberModal .role-badge {
        font-size: 0.65rem; font-weight: 600; padding: 2px 8px; border-radius: 4px; color: #64748b; background: #f1f5f9;
    }
</style>

<div class="container-fluid py-4 px-md-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-2 small fw-medium">
            <li class="breadcrumb-item">
                @if(auth()->user()->role == 'student')
                <a href="{{ route('student.dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
                @else
                <a href="{{ route('lecturer.dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
                @endif
            </li>
            <li class="breadcrumb-item"><a href="{{ route('classes.index') }}" class="text-decoration-none text-muted">{{ $group->classRoom->name ?? 'Class' }}</a></li>
            <li class="breadcrumb-item active">{{ $group->name }}</li>
        </ol>
    </nav>

    <div class="row align-items-end mb-5">
        <div class="col-md-7">
            <h1 class="fw-black mb-1 fw-bold"><strong>{{ $group->name }}</strong></h1>
            <p class="text-muted mb-0">{{ $group->description ?? 'No description provided for this group.' }}</p>
            <div class="mt-3 d-flex align-items-center gap-2">
                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">Update</span>
                <span class="text-muted small">| Last updated {{ $group->updated_at ? $group->updated_at->diffForHumans() : 'recently' }}</span>
            </div>
        </div>
        <div class="col-md-5">  
            <div class="d-flex align-items-stretch gap-3">
                <!-- Progress Container -->
                <div class="progress-container flex-grow-1 d-flex flex-column justify-content-center">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold small">Group Completion</span>
                        <div class="text-end">
    
                            <span class="fw-bold text-primary h4 mb-0">{{ $completionPercentage ?? 0 }}%</span>
                        </div>
                    </div>
                    <div class="progress mb-3" style="height: 8px;">
                        <div class="progress-bar" role="progressbar" style="width: {{ $completionPercentage ?? 0 }}%;" aria-valuenow="{{ $completionPercentage ?? 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <button class="btn btn-light w-100 btn-sm fw-bold border" onclick="location.reload()">
                        <span class="material-symbols-outlined fs-6 align-middle me-1">sync</span> Recalculate
                    </button>
                </div>

                <!-- Buttons Stacked Next to Progress -->
                <div class="d-flex flex-column gap-2 justify-content-center">
                     <!-- Add Member Button -->
                     @if(!$isLecturer)
                     <button class="btn btn-primary fw-bold text-nowrap" data-bs-toggle="modal" data-bs-target="#addMemberModal">
                        <span class="material-symbols-outlined align-middle me-1">person_add</span>
                        Add Member
                    </button>
                    @endif
                    <!-- Team Management Button -->
                    <button class="btn btn-primary fw-bold text-nowrap" data-bs-toggle="modal" data-bs-target="#teamModal">
                        <span class="material-symbols-outlined align-middle me-2">groups</span>
                        Team Management
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex overflow-auto pb-4" id="board-container">
        <!-- Render Lists Dynamic -->
        @forelse($group->lists as $list)
        <div class="kanban-column" id="list-{{ $list->id }}" draggable="true" ondragstart="drag(event)" ondrop="drop(event)" ondragover="allowDrop(event)" ondragleave="dragLeave(event)">
            <div class="d-flex justify-content-between align-items-center px-2 mb-3 handle">
                <h6 class="fw-bold mb-0 text-uppercase d-flex align-items-center gap-2" style="cursor: pointer;" onclick="editListName(this, {{ $list->id }})">
                    <span class="list-name">{{ $list->name }}</span> 
                    <span class="badge bg-secondary rounded-pill count-badge">{{ $list->cards->count() }}</span>
                </h6>
                <div class="dropdown">
                    <button class="btn btn-link text-muted p-0" type="button" data-bs-toggle="dropdown">
                        <span class="material-symbols-outlined">more_horiz</span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item text-danger small fw-bold" href="#" onclick="deleteList({{ $list->id }}); return false;">Delete List</a></li>
                    </ul>
                </div>
            </div>

            <!-- Render Cards -->
            <div class="kanban-cards-wrapper custom-scrollbar">
                @foreach($list->cards as $card)
                <div class="kanban-card" id="card-{{ $card->id }}" draggable="true" ondragstart="drag(event)" onclick='openTaskModal(@json($card))'>
                    @if($card->label)
                    <span class="badge-custom bg-purple bg-opacity-10 text-info" style="background-color: #f3e8ff; color: #7e22ce;">{{ $card->label }}</span>
                    @endif
                    
                    <div class="d-flex justify-content-between align-items-start mb-3 mt-2">
                        <div class="d-flex align-items-start gap-2">
                            <!-- Completion Checkbox -->
                             <div class="form-check">
                                <input class="form-check-input rounded-circle border-2" type="checkbox" style="width: 1.2rem; height: 1.2rem; cursor: pointer; border-color: #cbd5e1;" 
                                    onclick="toggleCardCompletion(event, {{ $card->id }}, {{ $card->is_completed }}, {{ $card->checklists->count() }}, {{ $card->progress_percent ?? 0 }})"
                                    {{ $card->is_completed ? 'checked' : '' }}>
                            </div>
                            <p class="small fw-semibold mb-0 text-break pe-2 {{ $card->is_completed ? 'text-decoration-line-through text-muted' : '' }}" id="card-name-{{ $card->id }}">{{ $card->name }}</p>
                        </div>

                        @if($card->priority == 1) <span class="small fw-bold text-uppercase priority-high" style="font-size: 0.8rem;">High</span>
                        @elseif($card->priority == 2) <span class="small fw-bold text-uppercase priority-medium" style="font-size: 0.8rem;">Medium</span>
                        @elseif($card->priority == 3) <span class="small fw-bold text-uppercase priority-low" style="font-size: 0.8rem;">Low</span>
                        @endif
                    </div>
                    
                    @if($card->checklists->count() > 0)
                    <div class="progress mb-3" style="height: 4px;">
                        <div class="progress-bar" style="width: {{ $card->progress_percent ?? 0 }}%;"></div>
                    </div>
                    @endif

                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex gap-2">
                             @if($card->description)
                                <span class="material-symbols-outlined text-muted fs-6" title="Has description">subject</span>
                             @endif
                             @if($card->checklists->count() > 0)
                                <span class="material-symbols-outlined text-muted fs-6" title="Checklist ({{ $card->progress_percent ?? 0 }}%)">check_box</span>
                             @endif
                        </div>
                        @if($card->assignedUser)
                        <div class="avatar-sm" style="background-image: url('{{ $card->assignedUser->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($card->assignedUser->name).'&background=random' }}')" title="Assigned to {{ $card->assignedUser->name }}"></div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            
            <button class="btn btn-transparent w-100 text-start text-muted btn-sm fw-bold mt-2" onclick="openCreateCardModal({{ $list->id }})">
                <span class="material-symbols-outlined fs-6 align-middle me-1">add</span> Add a card
            </button>
        </div>
        @empty
            <!-- Default structure if no lists exist -->
            <div class="text-center w-100 py-5">
                <p class="text-muted">No lists found. Start by adding one!</p>
                <div class="kanban-column bg-transparent border-0 shadow-none" style="min-width: 320px;">
                    <button class="btn btn-light w-100 py-3 fw-bold text-muted border-dashed" data-bs-toggle="modal" data-bs-target="#createListModal">
                        <span class="material-symbols-outlined align-middle me-2">add</span> Add another list
                    </button>
                </div>
            </div>
        @endforelse

        <!-- Add List Button Column -->
         <div class="kanban-column bg-transparent border-0 shadow-none" style="min-width: 320px;">
            <button class="btn btn-light w-100 py-3 fw-bold text-muted border-dashed" data-bs-toggle="modal" data-bs-target="#createListModal">
                <span class="material-symbols-outlined align-middle me-2">add</span> Add another list
            </button>
        </div>
    </div>
</div>

<!-- Team Modal -->
<div class="modal fade" id="teamModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" style="max-width: 95vw !important;">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold"><span class="material-symbols-outlined align-middle text-primary me-2">groups</span> Team Management & Grades</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                
                @php
                    $allGroupCards = $group->lists->pluck('cards')->flatten(); 
                @endphp

                <!-- Iterate Members -->
                @foreach($group->members as $member)
                @php
                    // Calculate Member Stats
                    $assignedCards = $allGroupCards->where('assigned_to', $member->id);
                    $totalAssigned = $assignedCards->count();
                    $completedAssigned = $assignedCards->where('is_completed', true)->count();
                    $percent = $totalAssigned > 0 ? round(($completedAssigned / $totalAssigned) * 100) : 0;
                @endphp

                <div class="card mb-3 border shadow-sm">
                    <!-- Card Header / Collapsed Trigger -->
                    <div class="card-header bg-white d-flex align-items-center justify-content-between p-3" 
                         data-bs-toggle="collapse" 
                         data-bs-target="#collapseMember{{ $member->id }}" 
                         aria-expanded="false" 
                         style="cursor: pointer;">
                        
                        <div class="d-flex align-items-center gap-3">
                             <div class="avatar-sm" style="width: 40px; height: 40px; background-image: url('{{ $member->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($member->name).'&background=random' }}')"></div>
                             <div>
                                 <h6 class="fw-bold mb-0">{{ $member->name }}</h6>
                                 <span class="text-primary small fw-semibold">{{ $member->pivot->role }}</span>
                             </div>
                        </div>

                        <div class="d-flex align-items-center gap-4">
                             <!-- Summary Progress -->
                             <div class="d-none d-md-block text-end" style="min-width: 100px;">
                                @php
                                    $memberScoreObj = $group->scores->firstWhere('user_id', $member->id);
                                @endphp
                                @if($memberScoreObj)
                                    <div class="small fw-bold text-success mb-1">Score: {{ $memberScoreObj->score }}/10</div>
                                @endif
                                <div class="small fw-bold {{ $percent == 100 ? 'text-success' : 'text-secondary' }}">{{ $percent }}% Done</div>
                                <div class="progress mt-1" style="height: 5px;">
                                    <div class="progress-bar {{ $percent == 100 ? 'bg-success' : 'bg-primary' }}" style="width: {{ $percent }}%;"></div>
                                </div>
                             </div>
                             
                             <span class="material-symbols-outlined text-muted">expand_more</span>
                        </div>
                    </div>

                    <!-- Collapsible Content -->
                    <div class="collapse" id="collapseMember{{ $member->id }}">
                        <div class="card-body bg-light">
                            <div class="row g-4">
                                <!-- Section 1: Work Percentage / Details -->
                                <div class="col-md-6 border-end">
                                    <h6 class="text-uppercase text-secondary small fw-bold mb-3">Work Statistics</h6>
                                    
                                    <div class="d-flex justify-content-between align-items-end mb-3">
                                        <div>
                                            <span class="display-6 fw-bold">{{ $percent }}%</span>
                                            <span class="d-block small text-muted">Tasks Completed</span>
                                        </div>
                                        <div class="text-end">
                                            <span class="h5 fw-bold mb-0">{{ $completedAssigned }} / {{ $totalAssigned }}</span>
                                            <span class="d-block small text-muted">Assigned Cards</span>
                                        </div>
                                    </div>

                                    <div class="progress mb-3" style="height: 10px;">
                                         <div class="progress-bar {{ $percent == 100 ? 'bg-success' : 'bg-primary' }}" style="width: {{ $percent }}%;"></div>
                                    </div>

                                   
                                </div>

                                <!-- Section 2: Grading Function -->
                                <div class="col-md-6">
                                    <h6 class="text-uppercase text-secondary small fw-bold mb-3">Grading & Feedback</h6>
                                    @php
                                        // Find score from eager loaded scores
                                        $scoreObj = $group->scores->firstWhere('user_id', $member->id);
                                    @endphp
                                    <form action="{{ route('grades.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="group_id" value="{{ $group->id }}">
                                        
                                        <div class="mb-3">
                                            <label class="small fw-bold mb-1">Individual Grade</label>
                                            <div class="input-group">
                                                <input type="number" name="grades[{{ $member->id }}][score]" class="form-control fw-bold" step="0.1" min="0" max="10" 
                                                    value="{{ $scoreObj ? $scoreObj->score : '' }}" 
                                                    placeholder="{{ $scoreObj ? '' : 'Not graded yet' }}" 
                                                    {{ ($isLecturer && !$group->is_graded) ? '' : 'disabled' }}>
                                                <span class="input-group-text fw-bold">/ 10</span>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="small fw-bold mb-1">Lecturer Comments</label>
                                            <textarea name="grades[{{ $member->id }}][feedback]" class="form-control" rows="3" 
                                                placeholder="{{ $scoreObj && $scoreObj->comment ? '' : 'No feedback yet' }}" 
                                                {{ ($isLecturer && !$group->is_graded) ? '' : 'disabled' }}>{{ $scoreObj ? $scoreObj->comment : '' }}</textarea>
                                        </div>

                                        @if($isLecturer)
                                            @if(!$group->is_graded)
                                                <div class="d-flex gap-2">
                                                    <button type="submit" class="btn btn-light border btn-sm w-50 fw-bold">Save Draft</button>
                                                    <button type="submit" name="lock" value="1" class="btn btn-primary btn-sm w-50 fw-bold">Submit & Lock</button>
                                                </div>
                                            @else
                                                <div class="alert alert-warning py-2 small mb-2 d-flex align-items-center gap-2">
                                                    <span class="material-symbols-outlined fs-6">lock</span> Grades Locked
                                                </div>
                                                <button type="submit" formaction="{{ route('grades.unlock', $group->id) }}" class="btn btn-outline-secondary btn-sm w-100 fw-bold">Unlock Grading</button>
                                            @endif
                                        @endif
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Add Member Modal -->
<div class="modal fade" id="addMemberModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="max-width: 500px !important;">
        <form action="{{ route('groups.members.store') }}" method="POST" id="addMemberForm" class="w-100">
            @csrf
            <input type="hidden" name="group_id" value="{{ $group->id }}">
            <input type="hidden" name="user_id" id="selectedUserId">
            
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Add to Board</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-4" style="max-height: 65vh; overflow-y: auto;">
                    
                    <div class="search-wrapper mb-4 position-relative">
                        <label class="form-label d-block fw-semibold mb-2 small">Search Users</label>
                        <span class="material-symbols-outlined position-absolute" style="top: 40px; left: 12px; color: #64748b;">search</span>
                        <input type="text" class="form-control ps-5 py-2" id="memberSearchInput" placeholder="Search by name or email address...">
                    </div>

                    <div class="mb-4">
                        <h3 class="section-title text-muted small fw-bold text-uppercase mb-3">Suggestions</h3>
                        
                        <div id="suggestionsList">
                            @forelse($eligibleStudents ?? [] as $student)
                            <div class="suggestion-item p-2 border rounded mb-2 d-flex align-items-center justify-content-between cursor-pointer" 
                                 onclick="selectUser(this, {{ $student->id }})">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="user-avatar rounded-circle" style="width: 40px; height: 40px; background-image: url('{{ $student->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($student->name).'&background=random' }}'); background-size: cover;"></div>
                                    <div>
                                        <div class="fw-bold small student-name">{{ $student->name }}</div>
                                        <div class="text-muted small student-email" style="font-size: 0.75rem;">{{ $student->email }}</div>
                                    </div>
                                </div>
                                <div class="check-badge rounded-circle bg-primary text-white d-none align-items-center justify-content-center" style="width: 24px; height: 24px;">
                                    <span class="material-symbols-outlined fs-6">check</span>
                                </div>
                            </div>
                            @empty
                            <p class="text-muted small text-center py-3">No eligible students found.</p>
                            @endforelse
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="mb-2">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="section-title text-muted small fw-bold text-uppercase mb-0">Current Members ({{ $group->members->count() }})</h3>
                        </div>

                        <div class="d-flex flex-column gap-2">
                            @foreach($group->members as $member)
                            <div class="member-item p-2 rounded d-flex align-items-center justify-content-between hover-bg-light">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="user-avatar rounded-circle" style="width: 36px; height: 36px; background-image: url('{{ $member->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($member->name).'&background=random' }}'); background-size: cover;"></div>
                                    <div>
                                        <div class="fw-medium small">{{ $member->name }}</div>
                                        <div class="text-muted small" style="font-size: 0.75rem;">{{ $member->email }}</div>
                                    </div>
                                </div>
                                <span class="badge bg-light text-secondary border">{{ $member->pivot->role }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-light text-secondary fw-bold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold d-flex align-items-center gap-2" id="btnAddMember" disabled>
                        <span>Add Member</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function selectUser(element, userId) {
        // Deselect all
        document.querySelectorAll('.suggestion-item').forEach(el => {
            el.classList.remove('selected', 'bg-primary-subtle', 'border-primary');
            el.querySelector('.check-badge').classList.add('d-none');
            el.querySelector('.check-badge').classList.remove('d-flex');
        });

        // Select clicked
        element.classList.add('selected', 'bg-primary-subtle', 'border-primary');
        element.querySelector('.check-badge').classList.remove('d-none');
        element.querySelector('.check-badge').classList.add('d-flex');

        // Update hidden input and enable button
        document.getElementById('selectedUserId').value = userId;
        document.getElementById('btnAddMember').disabled = false;
    }

    // Client-side search logic
    document.getElementById('memberSearchInput').addEventListener('input', function(e) {
        const term = e.target.value.toLowerCase();
        document.querySelectorAll('.suggestion-item').forEach(item => {
            const name = item.querySelector('.student-name').innerText.toLowerCase();
            const email = item.querySelector('.student-email').innerText.toLowerCase();
            if(name.includes(term) || email.includes(term)) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    });
</script>

<!-- Create List Modal -->
<div class="modal fade" id="createListModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('lists.store') }}" method="POST" class="w-100">
            @csrf
            <input type="hidden" name="group_id" value="{{ $group->id }}">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Add New List</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label fw-bold">List Name</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g., In Review" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold">Create List</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Create Card Modal -->
<div class="modal fade" id="createCardModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('cards.store') }}" method="POST" class="w-100">
            @csrf
            <input type="hidden" name="list_id" id="modalListId">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Add New Card</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Task Title</label>
                        <input type="text" name="name" class="form-control" required placeholder="What needs to be done?">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Assign To</label>
                        <select name="assigned_to" class="form-select">
                            <option value="">Unassigned</option>
                            @foreach($group->members as $member)
                                <option value="{{ $member->id }}">{{ $member->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                         <label class="form-label fw-bold">Due Date</label>
                         <input type="date" name="due_date" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                     <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold">Add Card</button>
                </div>
            </div>
        </form>
    </div>
</div>


<!-- Task Detail Modal -->
<div class="task-overlay" id="taskDetailModal">
    <div class="task-modal">
        <button class="btn btn-sm position-absolute top-0 end-0 m-3" style="z-index: 10;" onclick="closeTaskModal()">
            <span class="material-symbols-outlined text-secondary">close</span>
        </button>

        <div class="row g-0 flex-grow-1 overflow-hidden">
            <!-- Left Column: Details -->
            <div class="col-lg-8 p-4 p-md-5 border-end custom-scrollbar overflow-y-auto h-100">
                
                <div class="d-flex gap-3 mb-4">
                    <div class="flex-grow-1">
                        <input type="hidden" id="taskId">
                        <input type="text" id="taskTitle" class="editable-title border-0 bg-transparent" value="Task Title">
                        <!-- <p class="text-muted small ms-1">in list <span id="taskListValue" class="text-dark fw-medium">List Name</span></p> -->
                    </div>
                </div>

                <div class="row g-3 mb-5 ms-md-4">
                    <div class="col-sm-6 col-md-3">
                        <div class="property-label">Assignee</div>
                        <button class="property-btn">
                            <img id="taskAssigneeAvatar" src="https://ui-avatars.com/api/?name=Unassigned&background=e2e8f0" class="rounded-circle" width="20" height="20">
                            <span class="text-truncate" id="taskAssigneeName">Unassigned</span>
                        </button>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="property-label">Priority</div>
                        <select id="taskPriority" class="form-select border-0 bg-danger-subtle text-danger fw-medium" style="font-size: 0.875rem; border-radius: 0.5rem;">
                            <option value="1">High</option>
                            <option value="2">Medium</option>
                            <option value="3">Low</option>
                            <option value="0">None</option>
                        </select>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="property-label">Due Date</div>
                        <button class="property-btn">
                            <span class="material-symbols-outlined fs-6">calendar_month</span>
                            <span id="taskDueDate">--</span>
                        </button>
                    </div>
                </div>

                <div class="d-flex gap-3 mb-5">
                    <span class="material-symbols-outlined text-dark mt-1">description</span>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="mb-0 fw-bold">Description</h5>
                            <button class="btn btn-link btn-sm text-decoration-none text-muted p-0">Formatting Help</button>
                        </div>
                        <textarea id="taskDescription" class="form-control border-secondary-subtle p-3 shadow-sm" style="min-height: 150px; border-radius: 0.75rem; font-size: 0.875rem; line-height: 1.6;" placeholder="Add a more detailed description..."></textarea>
                    </div>
                </div>

                <div class="d-flex gap-3 mb-2">
                    <span class="material-symbols-outlined text-dark mt-1">check_box</span>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0 fw-bold">Subtasks</h5>
                        </div>
                        
                        <!-- Subtasks Container -->
                         <div id="subtaskListContainer">
                            <!-- Populated by JS -->
                         </div>
                        
                        <!-- Add Subtask Button (Visible if no checklist, or always?) -->
                        <button class="btn btn-secondary btn-sm fw-bold px-3 py-2 mt-2" onclick="addSubtask()">
                            <span class="material-symbols-outlined fs-6 align-middle">add</span>
                            Add Subtask
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right Column: Activity -->
            <div class="col-lg-4 bg-light bg-opacity-50 p-4 p-md-5 d-flex flex-column h-100 border-start custom-scrollbar overflow-y-auto">
                <div class="d-flex align-items-center gap-2 mb-4">
                    <h5 class="mb-0 fw-bold">Activity & Comment</h5>
                </div>

                <div class="position-relative mb-5 flex-grow-1">
                    <!-- Placeholder Activity -->
                    <div class="d-flex gap-3 mb-4 position-relative">
                        <div class="bg-primary-subtle border border-primary-subtle rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; z-index: 1;">
                            <span class="material-symbols-outlined fs-6 text-primary">history</span>
                        </div>
                        <div>
                            <p class="small mb-0"><strong>System</strong> created this task</p>
                            <span class="extra-small text-muted" style="font-size: 0.7rem;">Recently</span>
                        </div>
                    </div>
                </div>

                <div class="mt-auto">
                    <div class="position-relative">
                        <textarea class="form-control p-3 pe-5" rows="2" placeholder="Write a comment..." style="border-radius: 0.75rem; font-size: 0.875rem; resize: none;"></textarea>
                        <div class="position-absolute bottom-0 end-0 mb-2 me-2 d-flex gap-1">
                            <button class="btn btn-sm text-muted"><span class="material-symbols-outlined fs-5">attach_file</span></button>
                            <button class="btn btn-sm text-primary"><span class="material-symbols-outlined fs-5">send</span></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-4 border-top bg-light rounded-bottom-4 d-flex justify-content-between align-items-center">
            <span class="small text-muted d-none d-sm-inline">Last updated recently</span>
            <div class="d-flex gap-3 ms-auto">
                <button class="btn btn-light px-4 py-2 fw-medium border text-danger" onclick="deleteCard()">Delete Task</button>
                <button class="btn btn-light px-4 py-2 fw-medium border" onclick="closeTaskModal()">Cancel</button>
                <button class="btn btn-save px-4 py-2 fw-medium d-flex align-items-center gap-2" onclick="saveTaskChanges()">
                    Save Changes
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Create Subtask Modal -->
<div class="modal fade" id="createSubtaskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fs-6 fw-bold">Add Subtask</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="text" id="newSubtaskName" class="form-control" placeholder="Subtask name (e.g. Checklist)..." autofocus>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-sm btn-light fw-medium" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-sm btn-primary fw-bold" onclick="saveNewSubtask()">Create</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Item Modal -->
<div class="modal fade" id="addItemModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fs-6 fw-bold">Add Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="addItemChecklistId">
                <input type="text" id="addItemName" class="form-control" placeholder="Item name..." autofocus>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-sm btn-light fw-medium" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-sm btn-primary fw-bold" onclick="saveChecklistItem()">Add</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Create Card Modal Trigger
    function openCreateCardModal(listId) {
        document.getElementById('modalListId').value = listId;
        new bootstrap.Modal(document.getElementById('createCardModal')).show();
    }

    // Modal Logic
    let currentCard = null;

    function openTaskModal(card) {
        currentCard = card;
        const modal = document.getElementById('taskDetailModal');
        
        // Populate inputs
        document.getElementById('taskId').value = card.id;
        document.getElementById('taskTitle').value = card.name;
        document.getElementById('taskDescription').value = card.description || '';
        document.getElementById('taskPriority').value = card.priority !== null ? card.priority : 0;
        document.getElementById('taskDueDate').innerText = card.due_date ? new Date(card.due_date).toLocaleDateString() : '--';
        
        // Populate Assignee
        if (card.assigned_user) {
             document.getElementById('taskAssigneeName').innerText = card.assigned_user.name;
             document.getElementById('taskAssigneeAvatar').src = `https://ui-avatars.com/api/?name=${encodeURIComponent(card.assigned_user.name)}&background=random`;
        } else {
             document.getElementById('taskAssigneeName').innerText = 'Unassigned';
             document.getElementById('taskAssigneeAvatar').src = `https://ui-avatars.com/api/?name=Unassigned&background=e2e8f0`;
        }

        // Render Checklists
        renderChecklists(card.checklists || []);

        modal.style.display = 'flex';
    }

    function renderChecklists(checklists) {
        const container = document.getElementById('subtaskListContainer');
        container.innerHTML = '';

        checklists.forEach(checklist => {
            const items = checklist.items || [];
            const total = items.length;
            const checked = items.filter(i => i.is_checked).length;
            const percent = total > 0 ? Math.round((checked / total) * 100) : 0;

            let itemsHtml = '';
            items.forEach(item => {
                itemsHtml += `
                    <div class="d-flex align-items-center gap-2 mb-2 group-item">
                        <input type="checkbox" class="form-check-input mt-0" ${item.is_checked ? 'checked' : ''} onchange="toggleItem(${item.id}, this.checked)">
                        <input type="text" class="form-control form-control-sm border-0 bg-transparent flex-grow-1 ${item.is_checked ? 'text-decoration-line-through text-muted' : ''}" value="${item.name}" onchange="updateItemName(${item.id}, this.value)">
                        <button class="btn btn-sm text-danger opacity-0 group-hover-visible p-0" onclick="deleteItem(${item.id})">
                             <span class="material-symbols-outlined fs-6">delete</span>
                        </button>
                    </div>
                `;
            });

            const html = `
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                         <div class="d-flex align-items-center gap-2 flex-grow-1">
                            <input type="text" class="form-control form-control-sm border-0 bg-transparent fw-bold text-dark p-0" style="font-size: 1rem;" value="${checklist.name}" onchange="updateChecklistName(${checklist.id}, this.value)">
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <span class="small text-muted fw-bold">${percent}%</span>
                            <button class="btn btn-sm text-danger p-0" onclick="deleteChecklist(${checklist.id})">Delete</button>
                        </div>
                    </div>
                    <div class="progress mb-3" style="height: 6px;">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: ${percent}%"></div>
                    </div>
                    <div class="ms-1 ps-2 border-start border-2 border-light mb-3">
                        ${itemsHtml}
                        <button class="btn btn-light btn-sm fw-medium text-muted mt-2" onclick="openAddItemModal(${checklist.id})">Add an item</button>
                    </div>
                </div>
            `;
            container.innerHTML += html;
        });

        // CSS Helper for hover delete
        if(!document.getElementById('hoverStyles')) {
             const style = document.createElement('style');
             style.id = 'hoverStyles';
             style.innerHTML = `.group-item:hover .opacity-0 { opacity: 1 !important; }`;
             document.head.appendChild(style);
        }
    }

    // --- Subtask API Implementations ---

    function addSubtask() {
        if(!currentCard) return;
        document.getElementById('newSubtaskName').value = 'Checklist';
        new bootstrap.Modal(document.getElementById('createSubtaskModal')).show();
    }

    async function saveNewSubtask() {
        const name = document.getElementById('newSubtaskName').value;
        if(!name) return;

        try {
            const response = await fetch('/checklists', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                body: JSON.stringify({ card_id: currentCard.id, name: name, _token: '{{ csrf_token() }}' })
            });
            const data = await response.json();
            if(response.ok) {
                // Update local state
                if (!currentCard.checklists) currentCard.checklists = [];
                // Ensure items array exists
                data.checklist.items = [];
                currentCard.checklists.push(data.checklist);
                
                // Re-render
                renderChecklists(currentCard.checklists);
                
                // Close modal
                const modalEl = document.getElementById('createSubtaskModal');
                const modal = bootstrap.Modal.getInstance(modalEl);
                modal.hide();
            }
        } catch(e) { console.error(e); }
    }

    async function updateChecklistName(id, name) {
        try {
            await fetch(`/checklists/${id}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ name: name, _method: 'PUT', _token: '{{ csrf_token() }}' })
            });
        } catch(e) { console.error(e); }
    }

    async function deleteChecklist(id) {
        if(!confirm('Delete this checklist?')) return;
        try {
            const response = await fetch(`/checklists/${id}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                body: JSON.stringify({ _method: 'DELETE', _token: '{{ csrf_token() }}' })
            });
            
            if (response.ok) {
                // Update local state
                currentCard.checklists = currentCard.checklists.filter(c => c.id !== id);
                renderChecklists(currentCard.checklists);
            }
        } catch(e) { console.error(e); }
    }

    function openAddItemModal(checklistId) {
        document.getElementById('addItemChecklistId').value = checklistId;
        document.getElementById('addItemName').value = '';
        new bootstrap.Modal(document.getElementById('addItemModal')).show();
    }

    async function saveChecklistItem() {
        const checklistId = parseInt(document.getElementById('addItemChecklistId').value);
        const name = document.getElementById('addItemName').value;
        if(!name) return;

        try {
            const response = await fetch('/checklist-items', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                body: JSON.stringify({ checklist_id: checklistId, name: name, _token: '{{ csrf_token() }}' })
            });
            const data = await response.json();

            if (response.ok) {
                // Update local state
                const checklist = currentCard.checklists.find(c => c.id === checklistId);
                if (checklist) {
                    if (!checklist.items) checklist.items = [];
                    checklist.items.push(data.item);
                    renderChecklists(currentCard.checklists);
                }
                
                // Close modal
                const modalEl = document.getElementById('addItemModal');
                const modal = bootstrap.Modal.getInstance(modalEl);
                modal.hide();
            } else {
                console.error('Error adding item:', data);
                alert('Failed to add item. Please try again.');
            }
        } catch(e) {
            console.error(e);
        }
    }

    async function deleteItem(id) {
        if(!confirm('Delete item?')) return;
        try {
            const response = await fetch(`/checklist-items/${id}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ _method: 'DELETE', _token: '{{ csrf_token() }}' })
            });

            if (response.ok) {
                // Update local state
                currentCard.checklists.forEach(checklist => {
                   if (checklist.items) {
                       checklist.items = checklist.items.filter(i => i.id !== id);
                   }
                });
                renderChecklists(currentCard.checklists);
            }
        } catch(e) { console.error(e); }
    }

    async function toggleItem(id, checked) {
        try {
            // Optimistic update
            currentCard.checklists.forEach(checklist => {
                const item = checklist.items ? checklist.items.find(i => i.id === id) : null;
                if (item) item.is_checked = checked ? 1 : 0; // Ensure 1/0 for consistency
            });
            renderChecklists(currentCard.checklists);

            await fetch(`/checklist-items/${id}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ is_checked: checked, _method: 'PUT', _token: '{{ csrf_token() }}' })
            });
        } catch(e) { console.error(e); }
    }

    async function updateItemName(id, name) {
        try {
            await fetch(`/checklist-items/${id}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ name: name, _method: 'PUT', _token: '{{ csrf_token() }}' })
            });
        } catch(e) { console.error(e); }
    }


    function closeTaskModal() {
        document.getElementById('taskDetailModal').style.display = 'none';
        currentCard = null;
    }

    // Close on overlay click
    document.getElementById('taskDetailModal').addEventListener('click', (e) => {
        if (e.target.id === 'taskDetailModal') {
            closeTaskModal();
        }
    });

    async function saveTaskChanges() {
        if(!currentCard) return;

        const cardId = document.getElementById('taskId').value;
        const name = document.getElementById('taskTitle').value;
        const description = document.getElementById('taskDescription').value;
        const priority = document.getElementById('taskPriority').value;

        const payload = {
            name: name,
            description: description,
            priority: parseInt(priority),
            _method: 'PUT', // Method spoofing for Laravel
            _token: '{{ csrf_token() }}'
        };

        try {
            const response = await fetch(`/cards/${cardId}`, {
                method: 'POST', // Only POST/GET supported by default in HTML forms, spoofing PUT
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            if(response.ok) {
                // Success
                // location.reload(); 
                // Better UX: Update DOM elements if only text/color changed, but reload guarantees state.
                location.reload(); 
            } else {
                alert('Failed to save changes.');
                console.error(await response.text());
            }
        } catch(e) {
            console.error(e);
            alert('Error saving changes.');
        }
    }

    // --- List Renaming Logic ---
    function editListName(element, listId) {
        const nameSpan = element.querySelector('.list-name');
        const currentName = nameSpan.innerText;
        
        // Create input
        const input = document.createElement('input');
        input.type = 'text';
        input.value = currentName;
        input.className = 'form-control form-control-sm fw-bold text-uppercase';
        input.style.width = '100%'; // Adjust width as needed
        
        input.onblur = async function() {
             const newName = input.value;
             if(newName && newName !== currentName) {
                 await updateListName(listId, newName);
                 nameSpan.innerText = newName;
             }
             element.classList.remove('d-none');
             input.remove();
        };

        input.onkeydown = function(e) {
            if(e.key === 'Enter') input.blur();
        };

        // Insert input after header and hide header
        element.classList.add('d-none');
        element.parentNode.insertBefore(input, element);
        input.focus();
    }

    async function updateListName(listId, name) {
        try {
            await fetch(`/lists/${listId}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ name: name, _method: 'PUT', _token: '{{ csrf_token() }}' })
            });
        } catch(e) { console.error('Error updating list name:', e); }
    }

    // --- Advanced Drag & Drop (List & Card) ---
    let dragType = null; // 'card' or 'list'
    let dragSrcListId = null;

    function drag(ev) {
        ev.stopPropagation(); // Prevent bubbling
        
        if (ev.target.classList.contains('kanban-column')) {
            dragType = 'list';
            ev.dataTransfer.setData("text", ev.target.id);
            ev.dataTransfer.effectAllowed = 'move';
            ev.target.classList.add('dragging-list');
        } else if (ev.target.classList.contains('kanban-card')) {
            dragType = 'card';
            ev.dataTransfer.setData("text", ev.target.id);
            ev.dataTransfer.effectAllowed = 'move';
            ev.target.classList.add('dragging');
            const col = ev.target.closest('.kanban-column');
            if(col) dragSrcListId = col.id;
        }
    }

    function allowDrop(ev) {
        ev.preventDefault();
        
        if (dragType === 'list') {
            const container = document.getElementById('board-container');
            const afterElement = getDragAfterColumn(container, ev.clientX);
            const draggingList = document.querySelector('.dragging-list');
            
            if (draggingList) {
                if (afterElement == null) {
                    container.appendChild(draggingList);
                } else {
                    container.insertBefore(draggingList, afterElement);
                }
            }
            
        } else if (dragType === 'card') {
            const column = ev.target.closest('.kanban-column');
            if (column) {
                column.classList.add('drag-over');
                
                // Target the wrapper instead of the column
                const wrapper = column.querySelector('.kanban-cards-wrapper');
                if (!wrapper) return; // Safety check

                const afterElement = getDragAfterCard(wrapper, ev.clientY);
                const draggingCard = document.querySelector('.dragging');
                
                if (draggingCard) {
                     if (afterElement == null) {
                         wrapper.appendChild(draggingCard);
                     } else {
                         wrapper.insertBefore(draggingCard, afterElement);
                     }
                }
            }
        }
    }

    function getDragAfterCard(container, y) {
        const draggableElements = [...container.querySelectorAll('.kanban-card:not(.dragging)')];
        
        return draggableElements.reduce((closest, child) => {
            const box = child.getBoundingClientRect();
            const offset = y - box.top - box.height / 2;
            if (offset < 0 && offset > closest.offset) {
                return { offset: offset, element: child };
            } else {
                return closest;
            }
        }, { offset: Number.NEGATIVE_INFINITY }).element;
    }

    function getDragAfterColumn(container, x) {
        const draggableElements = [...container.querySelectorAll('.kanban-column:not(.dragging-list)')];
        
        return draggableElements.reduce((closest, child) => {
            const box = child.getBoundingClientRect();
            const offset = x - box.left - box.width / 2;
            if (offset < 0 && offset > closest.offset) {
                return { offset: offset, element: child };
            } else {
                return closest;
            }
        }, { offset: Number.NEGATIVE_INFINITY }).element;
    }

    function dragLeave(ev) {
        const col = ev.target.closest('.kanban-column');
        if(col) col.classList.remove('drag-over');
    }

    function drop(ev) {
        ev.preventDefault();
        
        // Cleanup visuals
        document.querySelectorAll('.drag-over').forEach(el => el.classList.remove('drag-over'));
        const draggingList = document.querySelector('.dragging-list');
        if(draggingList) draggingList.classList.remove('dragging-list');
        const draggingCard = document.querySelector('.dragging');
        if(draggingCard) draggingCard.classList.remove('dragging');

        if (dragType === 'list') {
             saveListOrder();
        } else if (dragType === 'card') {
             const column = ev.target.closest('.kanban-column');
             if(column) {
                 updateCounts();
                 saveCardOrder(column.id);
                 if(dragSrcListId && dragSrcListId !== column.id) {
                    saveCardOrder(dragSrcListId);
                 }
             }
        }
        
        dragType = null;
        dragSrcListId = null;
    }

    async function saveListOrder() {
        const container = document.getElementById('board-container');
        const listIds = Array.from(container.querySelectorAll('.kanban-column'))
                             .map(el => el.id.replace('list-', ''));
                             
        try {
            await fetch('/lists/reorder', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ lists: listIds, _token: '{{ csrf_token() }}' })
            });
            console.log('List order saved');
        } catch(e) { console.error(e); }
    }

    async function saveCardOrder(listId) {
        const listEl = document.getElementById(listId);
        if (!listEl) return;

        // Get all card IDs in current DOM order
        const cardIds = Array.from(listEl.querySelectorAll('.kanban-card'))
                             .map(el => el.id.replace('card-', ''));
        const dbListId = listId.replace('list-', '');

        try {
            await fetch('/cards/reorder', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    list_id: dbListId,
                    cards: cardIds,
                    _token: '{{ csrf_token() }}'
                })
            });
            console.log('Order saved for list ' + dbListId);
        } catch(e) {
            console.error('Failed to save card order:', e);
        }
    }

    function updateCounts() {
        document.querySelectorAll('.kanban-column').forEach(col => {
            const count = col.querySelectorAll('.kanban-card').length;
            const badge = col.querySelector('.count-badge');
            if(badge) badge.innerText = count;
        });
    }
    async function toggleCardCompletion(event, cardId, isCompleted, hasChecklists, progressPercent) {
        event.stopPropagation(); // Prevent opening modal
        
        // Ensure values are numbers
        hasChecklists = parseInt(hasChecklists);
        progressPercent = parseFloat(progressPercent);
        
        console.log('Toggling card:', { cardId, isCompleted, hasChecklists, progressPercent });

        // Validation: If has checklists, must be 100% complete to check
        if (hasChecklists > 0 && progressPercent < 100 && !isCompleted) {
            alert('You must complete all subtasks before marking the card as complete.');
            event.target.checked = false; // Revert check
            return;
        }

        const newStatus = !isCompleted;
        console.log('Sending update:', newStatus);
        
        try {
            const response = await fetch(`/cards/${cardId}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ 
                    is_completed: newStatus ? 1 : 0, 
                    _method: 'PUT', 
                    _token: '{{ csrf_token() }}' 
                })
            });
            
            if(!response.ok) {
                 console.error('Update failed:', await response.text());
                 alert('Failed to update status.');
                 event.target.checked = isCompleted;
            } else {
                 location.reload(); 
            }
        } catch(e) {
            console.error('Error updating card completion:', e);
            event.target.checked = isCompleted; // Revert on error
        }
    }

    async function deleteList(id) {
        if(!confirm('Are you sure you want to delete this list? All cards in it will be deleted.')) return;
        try {
            const response = await fetch(`/lists/${id}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                body: JSON.stringify({ _method: 'DELETE', _token: '{{ csrf_token() }}' })
            });

            if(response.ok) {
                location.reload(); 
            } else {
                 alert('Failed to delete list.');
            }
        } catch(e) { console.error(e); }
    }

    async function deleteCard() {
        if(!currentCard) return;
        if(!confirm('Delete this card?')) return;
        
        try {
            const response = await fetch(`/cards/${currentCard.id}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                body: JSON.stringify({ _method: 'DELETE', _token: '{{ csrf_token() }}' })
            });

            if(response.ok) {
                location.reload(); 
            } else {
                alert('Failed to delete card.');
            }
        } catch(e) { console.error(e); }
    }
</script>
@endsection
