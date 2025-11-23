@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Participant Detail</h2>
        <div>
            <a href="{{ route('participants.print', $participant->id) }}" class="btn btn-danger">
                <i class="bi bi-file-pdf"></i> Print ID Card (PDF)
            </a>
            <a href="{{ route('participants.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>

    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <h3 class="card-title text-primary">{{ $participant->name }}</h3>
            <p class="mb-1"><strong>Email:</strong> {{ $participant->email }}</p>
            <p class="mb-0"><strong>Phone:</strong> {{ $participant->phone }}</p>
        </div>
    </div>

    <h4>Enrolled Classes</h4>

    <div class="d-flex flex-wrap gap-2 mb-4">
        @forelse($participant->courses as $course)
            <div class="card border-primary mb-2" style="min-width: 200px;">
                <div class="card-body p-3">
                    <h6 class="card-title fw-bold">{{ $course->name }}</h6>
                    <p class="card-text small text-muted mb-2">{{ $course->instructor }}</p>

                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge bg-primary">Active</span>

                        <form action="{{ route('participants.detach', [$participant->id, $course->id]) }}" method="POST" onsubmit="return confirm('Unenroll from this class?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm py-0" style="font-size: 0.8rem;">Remove</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-muted">No classes enrolled.</p>
        @endforelse
    </div>

@endsection
