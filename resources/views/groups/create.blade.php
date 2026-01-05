@extends('layouts.student_dashboard')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0" style="border-radius: 12px;">
                <div class="card-header bg-white border-0 py-3">
                    <h4 class="mb-0 fw-bold">Create New Group</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('groups.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold small text-uppercase text-secondary">Group Name</label>
                            <input type="text" class="form-control" id="name" name="name" required placeholder="e.g. Project Team Alpha">
                        </div>

                        <div class="mb-3">
                            <label for="class_id" class="form-label fw-bold small text-uppercase text-secondary">Select Class</label>
                            <select class="form-select" id="class_id" name="class_id" required>
                                <option value="" selected disabled>Choose a class...</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                                @endforeach
                            </select>
                            <div class="form-text">You must belong to a class to create a group in it.</div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label fw-bold small text-uppercase text-secondary">Description (Optional)</label>
                            <textarea class="form-control" id="description" name="description" rows="3" placeholder="What is this group for?"></textarea>
                        </div>

                        <div class="mb-4 form-check">
                            <input type="checkbox" class="form-check-input" id="is_public" name="is_public" value="1" checked>
                            <label class="form-check-label" for="is_public">Public Group (Other class members can join)</label>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('student.dashboard') }}" class="btn btn-light text-secondary fw-bold">Cancel</a>
                            <button type="submit" class="btn btn-primary fw-bold" style="background-color: var(--primary-color); border: none;">Create Group</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
