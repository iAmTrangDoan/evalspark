@extends('layouts.lecturer_dashboard')

@section('content')
<style>
    :root {
        --primary-color: #0078bd;
        --bg-light: #f5f7f8;
        --surface: #ffffff;
        --shadow-soft: 0 2px 15px rgba(0, 0, 0, 0.04);
        --shadow-hover: 0 10px 25px rgba(0, 0, 0, 0.08);
    }

    .fw-black { font-weight: 900; }

    /* Class Card (Modified for Lecturer Grid) */
    .class-card {
        background: var(--surface, #fff);
        border: 1px solid var(--border-color, #dee2e6);
        border-radius: 12px;
        box-shadow: 0 1px 1px rgba(9, 30, 66, 0.25), 0 0 1px rgba(9, 30, 66, 0.31);
        transition: transform 0.3s, box-shadow 0.3s;
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .class-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 4px 8px -2px rgba(9, 30, 66, 0.25), 0 0 1px rgba(9, 30, 66, 0.31);
    }

    .class-card .top-bar {
        height: 4px;
        width: 100%;
    }

    /* Toolbar & Inputs */
    .search-container { position: relative; }
    .search-container .material-symbols-outlined {
        position: absolute; left: 1rem; top: 50%;
        transform: translateY(-50%); color: #94a3b8;
    }
    .search-input {
        padding-left: 3rem; border-radius: 0.75rem;
        border: 1px solid #e2e8f0; background-color: white;
        transition: all 0.2s;
    }
    .search-input:focus { border-color: var(--primary-color); box-shadow: 0 0 0 4px rgba(0, 120, 189, 0.1); }

    .btn-primary-custom {
        background-color: var(--primary-color);
        border: none; color: white; font-weight: 700;
        padding: 0.75rem 1.5rem; border-radius: 0.75rem;
        box-shadow: 0 4px 12px rgba(0, 120, 189, 0.2);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        transition: all 0.2s;
    }
    .btn-primary-custom:hover {
        background-color: #00629b;
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(0, 120, 189, 0.3);
    }
</style>

<div class="container-xxl">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-4 mb-5">
        <div>
            <h1 class="fw-black mb-1 fw-bold">{{ $isArchived ? 'Archived Classes' : 'My Classes' }}</h1>
            <p class="text-muted mb-0">
                {{ $isArchived ? 'View and restore your archived courses.' : 'Manage your active courses, track student progress, and organize groups.' }}
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('classes.index', ['archived' => $isArchived ? 'false' : 'true']) }}" class="btn {{ $isArchived ? 'btn-outline-primary' : 'btn-outline-secondary' }} fw-bold d-flex align-items-center gap-2">
                <span class="material-symbols-outlined">{{ $isArchived ? 'arrow_back' : 'archive' }}</span>
                {{ $isArchived ? 'Back to Active Classes' : 'View Archived Classes' }}
            </a>
            @if(!$isArchived)
            <button type="button" class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#createClassModal">
                <span class="material-symbols-outlined">add</span>
                Create Class
            </button>
            @endif
        </div>
    </div>

    <!-- Search & Filter Bar -->
    <div class="card border-0 shadow-sm rounded-4 p-2 mb-5">
        <form action="{{ route('classes.index') }}" method="GET" class="row g-2 align-items-center mb-0">
            @if($isArchived)
                <input type="hidden" name="archived" value="true">
            @endif
            <div class="col-md-8">
                <div class="search-container">
                    <span class="material-symbols-outlined">search</span>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-lg border-0 search-input" placeholder="Search by class name, code...">
                </div>
            </div>
            <div class="col-md-4 d-flex align-items-center gap-2 ps-md-4 border-start">
                 <select name="semester" class="form-select border-0 fw-bold bg-transparent shadow-none" onchange="this.form.submit()">
                    <option value="All Semesters">All Semesters</option>
                    @foreach($semesters as $semester)
                        <option value="{{ $semester }}" {{ request('semester') == $semester ? 'selected' : '' }}>{{ $semester }}</option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>

    <!-- Class Cards Grid -->
    <div class="row g-4">
        @forelse($classes as $index => $class)
        @php
            $colors = ['#0d6efd', '#a855f7', '#198754', '#ffc107', '#dc3545'];
            $themeColor = $isArchived ? '#6c757d' : $colors[$index % count($colors)]; // Grey for archived
            $badgeBg = $themeColor . '1a'; // 10% opacity hex
            
            $progress = rand(20, 95);
            $progressColor = $progress > 75 ? 'success' : ($progress > 40 ? 'warning' : 'danger');
        @endphp
        <div class="col-lg-4 col-md-6">
            <div class="class-card">
                <div class="top-bar" style="background-color: {{ $themeColor }};"></div>
                <div class="p-4 flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start mb-3">

                        <div class="dropdown">
                            <button class="btn btn-sm text-muted p-0" type="button" data-bs-toggle="dropdown">
                                <span class="material-symbols-outlined">more_vert</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                <li><a class="dropdown-item small" href="{{ route('classes.show', $class->id) }}">View Details</a></li>
                                <li>
                                    <button class="dropdown-item small" type="button" data-bs-toggle="modal" data-bs-target="#editClassModal{{ $class->id }}">
                                        Edit Class
                                    </button>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                     <form action="{{ route('classes.destroy', $class->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item small {{ $isArchived ? 'text-success' : 'text-warning' }}" onclick="return confirm('{{ $isArchived ? 'Restore this class?' : 'Archive this class?' }}')">
                                            {{ $isArchived ? 'Restore Class' : 'Archive Class' }}
                                        </button>
                                     </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-1 {{ $isArchived ? 'text-muted' : '' }}">{{ $class->name }}</h5>
                    <p class="text-muted small">{{ $class->semester }}</p>
                    
                    <div class="row text-center g-2 my-4">
                        <div class="col-6">
                            <div class="bg-light p-3 rounded-3 border">
                                <h4 class="fw-bold mb-0">{{ $class->students_count }}</h4>
                                <small class="text-muted fw-bold" style="font-size: 10px;">STUDENTS</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light p-3 rounded-3 border">
                                <h4 class="fw-bold mb-0">{{ $class->groups_count }}</h4>
                                <small class="text-muted fw-bold" style="font-size: 10px;">GROUPS</small>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="d-flex justify-content-between small mb-1">
                            <span class="text-muted fw-bold">Grading Progress</span>
                            <span class="text-{{ $progressColor }} fw-bold">{{ $progress }}%</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-{{ $progressColor }}" style="width: {{ $progress }}%"></div>
                        </div>
                    </div>
                </div>
                <div class="p-3 border-top text-center bg-light">
                    <a href="{{ route('classes.show', $class->id) }}" class="text-decoration-none text-primary fw-bold small d-flex align-items-center justify-content-center gap-2">
                        View Details <span class="material-symbols-outlined fs-6">arrow_forward</span>
                    </a>
                </div>
            </div>

            <!-- Edit Class Modal -->
            <div class="modal fade" id="editClassModal{{ $class->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <form action="{{ route('classes.update', $class->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-content shadow-lg border-0 rounded-4">
                        <div class="modal-header border-0 pt-4 px-4 pb-0">
                            <h5 class="modal-title fw-black d-flex align-items-center gap-2">
                                <span class="material-symbols-outlined text-primary fs-3">edit_note</span>
                                Edit Class
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-uppercase text-muted">Class Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><span class="material-symbols-outlined text-muted fs-5">school</span></span>
                                        <input type="text" name="name" class="form-control bg-light border-0 py-2" value="{{ $class->name }}" required>
                                    </div>
                                </div>
        
                                <div class="col-md-3">
                                    <label class="form-label fw-bold small text-uppercase text-muted">Join Code</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><span class="material-symbols-outlined text-muted fs-5">key</span></span>
                                        <input type="text" name="join_code" class="form-control bg-light border-0 py-2" value="{{ $class->join_code }}" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label fw-bold small text-uppercase text-muted">Semester</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><span class="material-symbols-outlined text-muted fs-5">calendar_month</span></span>
                                        <input type="text" name="semester" class="form-control bg-light border-0 py-2" value="{{ $class->semester }}" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold small text-uppercase text-muted">Description (Optional)</label>
                                    <textarea name="description" class="form-control bg-light border-0" rows="3">{{ $class->description }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-light border-0 p-3 rounded-bottom-4">
                            <button type="button" class="btn btn-light fw-bold px-4" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary fw-bold px-4 shadow-sm">Save Changes</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        @empty
         <div class="col-12">
            <div class="text-center py-5 bg-white border border-dashed rounded-3">
                <div class="bg-light d-inline-flex p-3 rounded-circle mb-3 text-secondary">
                    <span class="material-icons-outlined fs-2">class</span>
                </div>
                <h5 class="fw-bold">No Classes Found</h5>
                <p class="text-muted mb-4">Create your first class to get started.</p>
                <a href="{{ route('classes.create') }}" class="btn btn-primary-custom">
                    Create Class
                </a>
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection
