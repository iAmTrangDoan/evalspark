@extends($isLecturer ? 'layouts.lecturer_dashboard' : 'layouts.student_dashboard')

@section('content')
<style>
    :root {
        --primary-blue: #0078bd;
        --hover-blue: #00629b;
        --bg-gray: #F4F5F7;
        --text-dark: #172B4D;
        --border-color: #dfe1e6;
        --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    /* Hero Section */
    .hero-card {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 32px;
        margin-bottom: 32px;
        position: relative;
        overflow: hidden;
        box-shadow: var(--card-shadow);
    }
    .hero-card::after {
        content: '';
        position: absolute;
        right: -20px;
        top: 0;
        bottom: 0;
        width: 30%;
        background: #f8f9fa;
        transform: skewX(-15deg);
        z-index: 0;
    }
    .hero-content { position: relative; z-index: 1; }

    /* Board Cards */
    .board-card {
        background: white;
        border: 1px solid var(--border-color);
        border-left: 6px solid var(--primary-blue);
        border-radius: 12px;
        padding: 24px;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.2s ease;
        text-decoration: none;
        color: inherit;
    }
    .board-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        border-color: #cbd5e1;
    }
    .category-badge {
        font-size: 10px;
        font-weight: 800;
        padding: 4px 8px;
        border-radius: 6px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Sidebar & Student List */
    .student-list-card {
        background: white;
        border-radius: 16px;
        border: 1px solid var(--border-color);
        overflow: hidden;
        box-shadow: var(--card-shadow);
    }
    .student-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 16px;
        margin: 4px 8px;
        border-radius: 10px;
        transition: background 0.2s ease;
        cursor: pointer;
    }
    .student-item:hover { background: #f1f2f4; }
    
    .avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .rep-star {
        position: absolute;
        top: -2px;
        right: -2px;
        background: #ffc107;
        color: white;
        border-radius: 50%;
        width: 16px;
        height: 16px;
        font-size: 10px !important;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid white;
    }

    /* Deadline Widget */
    .deadline-widget {
        background: linear-gradient(135deg, #0078bd 0%, #00629b 100%);
        color: white;
        border-radius: 16px;
        padding: 24px;
        margin-top: 24px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 20px rgba(0, 120, 189, 0.3);
    }

    /* Utilities */
    .btn-primary-custom {
        background-color: var(--primary-blue);
        color: white;
        font-weight: 700;
        border-radius: 10px;
        padding: 10px 20px;
        border: none;
        transition: background 0.2s;
    }
    .btn-primary-custom:hover {
        background-color: var(--hover-blue);
        color: white;
    }

    .empty-card {
        border: 2px dashed #dfe1e6;
        background: #f9fafb;
        min-height: 180px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        color: #6b7280;
        transition: all 0.2s;
        cursor: pointer;
        height: 100%;
        width: 100%;
        border: none;
    }
    .empty-card:hover {
        border-color: var(--primary-blue);
        background: white;
        color: var(--primary-blue);
    }

    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #dfe1e6; border-radius: 10px; }
</style>

<div class="container-fluid py-2">
    <nav class="mb-4 small fw-medium text-muted d-flex align-items-center gap-2">
        <a href="{{ route('student.dashboard') }}" class="text-decoration-none text-muted d-flex align-items-center gap-1">
            <span class="material-symbols-outlined" style="font-size: 18px;">dashboard</span>
            <span>Dashboard</span>
        </a>
        <span class="material-symbols-outlined" style="font-size: 16px;">chevron_right</span>
        <a href="{{ route('classes.index') }}" class="text-decoration-none text-muted">Classes</a>
        <span class="material-symbols-outlined" style="font-size: 16px;">chevron_right</span>
        <span class="text-dark fw-bold"><strong>{{ $class->name }}</strong></span>
    </nav>

    <div class="hero-card">
        <div class="hero-content d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4">
            <div>
                <div class="d-flex align-items-center gap-3 mb-2">
                    <h1 class="h2 fw-extrabold mb-0"><strong>{{ $class->name }}</strong></h1>
                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3 border border-primary-subtle">{{ strtoupper($class->status ?? 'ACTIVE') }}</span>
                </div>
                <p class="text-muted fs-5 mb-0">
                    <p>{{ $class->semester }}</p> 
                    <p>{{ $class->lecturer->name ?? 'Unknown Lecturer' }}</p> 
                    <p>{{ $class->description }}</p>
                </p>
            </div>
            <div class="d-flex gap-2">
                @if($isLecturer)
                    <button class="btn btn-primary fw-bold px-4 rounded-3 d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined" style="font-size: 18px;">edit</span> Edit
                    </button>
                    <!-- Join Code Display for Lecturer -->
                    <button class="btn btn-light border fw-bold px-4 rounded-3 d-flex align-items-center gap-2" title="Join Code: {{ $class->join_code }}">
                         <span class="material-symbols-outlined" style="font-size: 18px;">key</span> {{ $class->join_code }}
                    </button>
                @else
                     <!-- Student View: Maybe show join code or leave copy button? usually students share it too -->
                     <button class="btn btn-light border p-2 rounded-3" title="Copy Join Code: {{ $class->join_code }}" onclick="navigator.clipboard.writeText('{{ $class->join_code }}')">
                        <span class="material-symbols-outlined">share</span>
                    </button>
                @endif
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="d-flex align-items-center gap-2">
                    <span class="material-symbols-outlined text-primary fw-bold">groups</span>
                    <h2 class="h5 mb-0 fw-bold">Activity Group</h2>
                    <span class="badge bg-light text-muted border rounded-pill ms-2">{{ $class->groups->count() }}</span>
                </div>
                <!-- Optional sorting placeholder -->
                <select class="form-select form-select-sm w-auto border-0 bg-transparent fw-bold shadow-none cursor-pointer">
                    <option>Recent Activity</option>
                    <option>Name A-Z</option>
                </select>
            </div>

            <div class="row g-4">
                @foreach($class->groups as $group)
                <div class="col-md-6">
                    <a href="{{ route('groups.show', $group) }}" class="board-card" style="border-left-color: #0078bd;">
                        <div>
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <span class="category-badge" style="background: #0078bd15; color: #0078bd;">
                                    PROJECT
                                </span>
                                <span class="material-symbols-outlined text-muted">more_horiz</span>
                            </div>
                            <h5 class="fw-bold text-dark lh-sm">{{ $group->name }}</h5>
                        </div>
                        <div class="d-flex align-items-center justify-content-between pt-3 border-top mt-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar bg-light d-flex align-items-center justify-content-center text-primary fw-bold overflow-hidden" 
                                     style="width: 32px; height: 32px; font-size: 12px; {{ $group->leader && $group->leader->avatar_url ? 'background-image: url(' . $group->leader->avatar_url . '); background-size: cover; background-position: center;' : '' }}">
                                    {{ $group->leader && $group->leader->avatar_url ? '' : substr($group->leader->name ?? 'U', 0, 1) }}
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="text-muted" style="font-size: 8px; font-weight: 800;">LEADER</span>
                                    <span class="fw-bold small">{{ $group->leader->name ?? 'Unknown' }}</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-1 text-muted bg-light px-2 py-1 rounded small fw-bold" style="font-size: 10px;">
                                <span class="material-symbols-outlined" style="font-size: 14px;">schedule</span> {{ $group->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach

                <!-- Create Group Button Logic -->
                @if(!$isLecturer)
                    <div class="col-md-6">
                        <button class="empty-card" data-bs-toggle="modal" data-bs-target="#createGroupModal">
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mb-2 shadow-sm" style="width: 48px; height: 48px;">
                                <span class="material-symbols-outlined">add</span>
                            </div>
                            <span class="fw-bold small">Create New Group</span>
                        </button>
                    </div>
                @endif
                
                <!-- Lecturer Create Button Removed as per request -->
            </div>
        </div>

        <div class="col-lg-4">
            <div class="student-list-card">
                <div class="p-4 border-bottom">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">Students</h5>
                        <span class="badge bg-light text-dark border px-2 py-1">{{ $class->students->count() }} Enrolled</span>
                    </div>
                    <div class="search-bar w-100 position-relative">
                        <input type="text" id="student-search" class="form-control" placeholder="Search students..." style="padding-left: 2.5rem; background: #f1f2f4; border: none;">
                         <span class="material-symbols-outlined text-muted position-absolute" style="left: 10px; top: 8px;">search</span>
                    </div>
                </div>
                
                <div id="students-container" class="custom-scrollbar" style="max-height: 450px; overflow-y: auto; padding: 10px 0;">
                    @foreach($class->students as $student)
                    <div class="student-item">
                        <div class="d-flex align-items-center gap-3">
                            <div class="position-relative">
                                <div class="avatar bg-secondary text-white d-flex align-items-center justify-content-center fw-bold overflow-hidden"
                                     style="{{ $student->avatar_url ? 'background-image: url(' . $student->avatar_url . '); background-size: cover; background-position: center;' : '' }}">
                                    {{ $student->avatar_url ? '' : substr($student->name, 0, 1) }}
                                </div>
                            </div>
                            <div class="d-flex flex-column">
                                <span class="fw-bold small text-dark">{{ $student->name }}</span>
                                <span class="text-muted" style="font-size: 11px;">{{ $student->email }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    
                    @if($class->students->count() === 0)
                        <div class="p-4 text-center text-muted small">No students enrolled yet.</div>
                    @endif
                </div>

                @if($isLecturer)
                <div class="p-3 bg-light border-top">
                    <!-- Placeholder action for adding student -->
                    <button class="btn btn-light border w-100 fw-bold text-primary d-flex align-items-center justify-content-center gap-2 py-2 rounded-3">
                        <span class="material-symbols-outlined">person_add</span> Add Student
                    </button>
                </div>
                @endif
            </div>

            <!-- <div class="deadline-widget">
                <div class="position-relative z-1">
                    <h6 class="fw-bold mb-1">Upcoming Deadlines</h6>
                    <p class="small mb-4 opacity-75">Submit project proposal in 3 days (Friday, 24/10).</p>
                    <button class="btn btn-sm px-4 fw-bold text-white border-0" style="background: rgba(255,255,255,0.2);">View Calendar</button>
                </div>
                <div style="position: absolute; right: -20px; top: -20px; width: 100px; height: 100px; background: rgba(255,255,255,0.1); border-radius: 50%; filter: blur(30px);"></div>
            </div> -->
        </div>
    </div>
</div>

<!-- Create Group Modal -->
@include('groups.partials.create_modal', ['class' => $class])

<script>
    document.getElementById('student-search').addEventListener('input', function(e) {
        const searchText = e.target.value.toLowerCase();
        const items = document.querySelectorAll('.student-item');
        
        items.forEach(item => {
            const name = item.querySelector('.fw-bold').innerText.toLowerCase();
            const email = item.querySelector('.text-muted').innerText.toLowerCase();
            
            if (name.includes(searchText) || email.includes(searchText)) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    });
</script>
@endsection
