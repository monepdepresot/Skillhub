@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Course Detail</h2>
        <a href="{{ route('courses.index') }}" class="btn btn-secondary">Back to Courses</a>
    </div>

    <div class="card mb-4 bg-light">
        <div class="card-body">
            <h3 class="text-primary">{{ $course->name }}</h3>
            <p><strong>Instructor:</strong> {{ $course->instructor }}</p>
            <p><strong>Description:</strong> {{ $course->description }}</p>
        </div>
    </div>

    <h4>Registered Participants ({{ $course->participants->count() }})</h4>

    <table class="table table-bordered table-hover mt-3">
        <thead class="table-dark">
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th width="200">Actions</th> </tr>
        </thead>
        <tbody>
            @forelse($course->participants as $participant)
            <tr>
                <td>{{ $participant->name }}</td>
                <td>{{ $participant->email }}</td>
                <td>{{ $participant->phone }}</td>
                <td>
                    <div class="d-flex gap-1">
                        <a href="{{ route('participants.show', $participant->id) }}" class="btn btn-info btn-sm text-white">
                            View
                        </a>

                        <form action="{{ route('participants.detach', [$participant->id, $course->id]) }}" method="POST" onsubmit="return confirm('Remove {{ $participant->name }} from this class?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                Remove
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center text-muted py-4">No participants registered yet.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
@endsection
