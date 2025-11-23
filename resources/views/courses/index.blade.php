@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Course List</h2>
        <a href="{{ route('courses.create') }}" class="btn btn-primary">Add New Course</a>
    </div>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Class Name</th>
                <th>Instructor</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($courses as $course)
            <tr>
                <td>{{ $course->name }}</td>
                <td>{{ $course->instructor }}</td>
                <td>{{ $course->description }}</td>
                <td>
                    <a href="{{ route('courses.show', $course->id) }}" class="btn btn-info btn-sm">Detail</a>
                    <a href="{{ route('courses.edit', $course->id) }}" class="btn btn-warning btn-sm">Edit</a>

                    <form action="{{ route('courses.destroy', $course->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this class?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">No classes found. Click "Add New Course" to start.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
@endsection
