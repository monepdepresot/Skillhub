@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Participant List</h2>
        <a href="{{ route('participants.create') }}" class="btn btn-primary">Add New Participant</a>
    </div>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Enrolled Classes</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($participants as $participant)
            <tr>
                <td>{{ $participant->name }}</td>
                <td>{{ $participant->email }}</td>
                <td>
                    @forelse($participant->courses as $course)
                        <span class="badge bg-secondary">{{ $course->name }}</span>
                    @empty
                        <span class="text-muted small">Not enrolled</span>
                    @endforelse
                </td>
                <td>
                    <a href="{{ route('participants.show', $participant->id) }}" class="btn btn-info btn-sm">Detail</a>
                    <a href="{{ route('participants.edit', $participant->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('participants.destroy', $participant->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete user?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
