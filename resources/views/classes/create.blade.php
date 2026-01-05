<!-- @extends('layouts.bootstrap')

@section('content') -->
<x-bootstrap-layout>
@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h4 class="mb-0">Create New Class</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('classes.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Class Name</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Software Engineering 101" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Semester</label>
                        <input type="text" name="semester" class="form-control" placeholder="e.g. Fall 2024" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('classes.index') }}" class="btn btn-outline-secondary me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">Create Class</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
</x-bootstrap-layout>
