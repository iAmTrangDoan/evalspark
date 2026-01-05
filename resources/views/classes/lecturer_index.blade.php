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
            <h1 class="fw-black mb-1 fw-bold">My Classes</h1>
            <p class="text-muted mb-0">Manage your active courses, track student progress, and organize groups.</p>
        </div>
        <button type="button" class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#createClassModal">
            <span class="material-symbols-outlined">add</span>
            Create Class
        </button>
    </div>

    <!-- Search & Filter Bar -->
    <div class="card border-0 shadow-sm rounded-4 p-2 mb-5">
        <form action="{{ route('classes.index') }}" method="GET" class="row g-2 align-items-center mb-0">
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
            $themeColor = $colors[$index % count($colors)];
            $badgeBg = $themeColor . '1a'; // 10% opacity hex
            
            $progress = rand(20, 95);
            $progressColor = $progress > 75 ? 'success' : ($progress > 40 ? 'warning' : 'danger');
        @endphp
        <div class="col-lg-4 col-md-6">
            <div class="class-card">
                <div class="top-bar" style="background-color: {{ $themeColor }};"></div>
                <div class="p-4 flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="badge fw-bold" style="background-color: {{ $badgeBg }}; color: {{ $themeColor }};">
                            {{ $class->class_code }}
                        </span>
                        <div class="dropdown">
                            <button class="btn btn-sm text-muted p-0" type="button" data-bs-toggle="dropdown">
                                <span class="material-symbols-outlined">more_vert</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                <li><a class="dropdown-item small" href="{{ route('classes.show', $class->id) }}">View Details</a></li>
                                <li><a class="dropdown-item small" href="{{ route('classes.edit', $class->id) }}">Edit Class</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                     <form action="{{ route('classes.destroy', $class->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item small text-danger" onclick="return confirm('Are you sure?')">Delete Class</button>
                                     </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-1">{{ $class->name }}</h5>
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
