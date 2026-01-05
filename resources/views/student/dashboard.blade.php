@extends('layouts.student_dashboard')

@section('content')
<style>
    /* Scoped Styles from User Design */
    :root {
        --primary: #0078bd;
        --bg-light: #f5f7f8;
        --surface: #ffffff;
        --text-main: #0c171d;
        --text-secondary: #457fa1;
        --border-color: #e2e8f0;
        --shadow-soft: 0 2px 8px rgba(0, 0, 0, 0.05);
        --shadow-hover: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
    }
    
    /* Cards */
    .card-custom {
        background: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: 0.75rem;
        box-shadow: var(--shadow-soft);
        overflow: hidden;
        transition: all 0.3s;

    }

    .card-hover:hover {
        box-shadow: var(--shadow-hover);
        transform: translateY(-2px);
    }

    /* Status Badges */
    .badge-status {
        font-size: 10px;
        font-weight: 700;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        text-transform: uppercase;
    }
    .badge-overdue { background: #fee2e2; color: #dc2626; }
    .badge-today { background: #fef3c7; color: #b45309; }
    .badge-doing { background: #dbeafe; color: #1d4ed8; }
    .badge-todo { background: #e2e8f0; color: #475569; }

    /* Avatars */
    .avatar-stack {
        display: flex;
        align-items: center;
    }
    .avatar-stack img, .avatar-stack .avatar-placeholder {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        border: 2px solid #fff;
        margin-left: -8px;
        object-fit: cover;
    }
    .avatar-stack img:first-child { margin-left: 0; }

    /* Helpers */
    .fw-black { font-weight: 900; }
    .text-xsmall { font-size: 11px; }
    
    .task-item {
        cursor: pointer;
        transition: background 0.2s;
        border-bottom: 1px solid #f1f5f9;
        text-decoration: none;
    }
    .task-item:hover { background-color: #f8fafc; }
    .task-item:last-child { border-bottom: none; }

    /* SCROLLING FIX */
    .task-list {
        max-height: 400px; /* Adjust height as needed */
        overflow-y: auto;
    }
    
    /* Custom Scrollbar for task-list */
    .task-list::-webkit-scrollbar {
        width: 6px;
    }
    .task-list::-webkit-scrollbar-track {
        background: transparent;
    }
    .task-list::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
</style>

<div class="container-fluid max-width-7xl">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-5">
        <div>
            <h1 class="fw-black h2 mb-1">Welcome back, {{ $user->name }}!</h1>
            <p class="text-muted mb-0"> You have {{ $user->tasks()->where('is_completed', false)->count() }} tasks requiring attention.</p>
        </div>
        <div class="bg-white px-3 py-2 rounded-3 border small fw-bold text-secondary shadow-sm d-flex align-items-center gap-2">
            <span class="material-symbols-outlined text-primary fs-5">calendar_today</span>
            {{ now()->format('M d, Y') }}
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-lg-8">
            <div class="card-custom">
                <div class="p-3 border-bottom d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3">
                    <div class="d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined text-primary">assignment_turned_in</span>
                        <h6 class="mb-0 fw-bold">My Assigned Tasks</h6>
                    </div>
                    <div class="d-flex gap-1 overflow-x-auto pb-1 pb-sm-0">
                        <button class="btn btn-light btn-sm text-xsmall fw-bold px-3">All</button>
                        <!-- Filters could be implemented with JS later -->
                    </div>
                </div>

                <div class="task-list">
                    @forelse($user->tasks()->with('taskList.group')->latest()->take(10)->get() as $task)
                    <div class="task-item p-4 d-flex align-items-start gap-3">
                        <div class="form-check p-0">
                            <input class="form-check-input ms-0 mt-1" type="checkbox" style="width: 18px; height: 18px;" {{ $task->is_completed ? 'checked' : '' }} disabled>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="small fw-bold text-dark {{ $task->is_completed ? 'text-decoration-line-through text-muted' : '' }}">{{ $task->name }}</span>
                                
                                @if($task->due_date && \Carbon\Carbon::parse($task->due_date)->isPast() && !$task->is_completed)
                                    <span class="badge-status badge-overdue">Overdue</span>
                                @elseif($task->due_date && \Carbon\Carbon::parse($task->due_date)->isToday() && !$task->is_completed)
                                    <span class="badge-status badge-today">Due Today</span>
                                @elseif($task->is_completed)
                                    <span class="badge-status badge-todo">Done</span>
                                @else
                                    <span class="badge-status badge-doing">Doing</span>
                                @endif
                            </div>
                            <div class="d-flex align-items-center gap-2 text-xsmall text-muted">
                                <div class="rounded-circle bg-purple" style="width: 8px; height: 8px; background: #a855f7;"></div>
                                <span>{{ $task->taskList->group->name ?? 'Unknown Group' }}</span>
                                @if($task->due_date)
                                <span>•</span>
                                <span class="{{ \Carbon\Carbon::parse($task->due_date)->isPast() ? 'text-danger fw-medium' : '' }}">
                                    Due {{ \Carbon\Carbon::parse($task->due_date)->format('M d') }}
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="avatar-stack">
                            <img src="{{ $user->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=random' }}" class="rounded-circle border" width="24" height="24">
                        </div>
                    </div>
                    @empty
                    <div class="p-4 text-center text-muted">
                        <p class="mb-0 small">No tasks assigned yet.</p>
                    </div>
                    @endforelse
                </div>

                <div class="p-3 bg-light text-center border-top">
                    <!-- Link to all tasks or groups -->
                    <button class="btn btn-link btn-sm text-decoration-none fw-bold text-primary">View All Tasks</button>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
                <span class="material-symbols-outlined text-muted fs-5">history</span>
                Recently Viewed
            </h6>
            <div class="d-flex flex-column gap-3">
                <!-- Using Groups as Recently Viewed for now -->
                @foreach($groups->take(3) as $group)
                <a href="{{ route('groups.show', $group->id) }}" class="card-custom card-hover p-3 text-decoration-none d-block" >
                    <div class="d-flex justify-content-between mb-2">
                        <div class="rounded bg-primary bg-opacity-10 text-primary p-2 d-flex">
                            <span class="material-symbols-outlined fs-5">folder_shared</span>
                        </div>
                        <span class="material-symbols-outlined text-muted fs-6">arrow_outward</span>
                    </div>
                    <h6 class="fw-bold text-dark mb-0">{{ $group->name }}</h6>
                    <p class="text-muted text-xsmall mb-0">{{ $group->classRoom->name ?? 'Class Group' }}</p>
                </a>
                @endforeach
            </div>
        </div>
    </div>

    <div class="pt-4 border-top">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold mb-0">My Active Groups</h5>
            <a href="{{ route('groups.index') }}" class="text-decoration-none small fw-bold text-primary">View All Groups</a>
        </div>

        <div class="mb-5">
            <div class="row g-4">
                @foreach($groups as $group)
                <div class="col-md-6 col-xl-3">
                    <a href="{{ route('groups.show', $group->id) }}" class="text-decoration-none">
                        <div class="card-custom card-hover p-4 h-100 d-flex flex-column gap-4">
                            <div class="d-flex justify-content-between align-items-start">
                                <h6 class="fw-bold text-dark mb-0">{{ $group->name }}</h6>
                                <div class="avatar-stack">
                                    <!-- Show first 3 members -->
                                    @foreach($group->members->take(3) as $member)
                                    <img src="{{ $member->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($member->name).'&background=random' }}" title="{{ $member->name }}">
                                    @endforeach
                                    @if($group->members->count() > 3)
                                    <div class="avatar-placeholder d-flex align-items-center justify-content-center bg-light text-xsmall fw-bold text-muted">+{{ $group->members->count() - 3 }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-xsmall text-muted fw-medium">Members</span>
                                    <span class="text-xsmall fw-bold text-primary">{{ $group->members->count() }}</span>
                                </div>
                                <!-- Progress Bar Placeholder (Requires Logic) -->
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar" style="width: 50%; background: var(--primary);"></div>
                                </div>
                                <p class="text-xsmall text-muted mt-2 mb-0">Class: {{ $group->classRoom->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
