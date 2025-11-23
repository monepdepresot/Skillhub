@extends('layouts.app')

@section('content')
    <h2>Edit Course</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('courses.update', $course->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Class Name</label>
            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name', $course->name) }}" minlength="10" placeholder="Course Name (minimum 10 char)" required>

            @error('name')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
            <div class="text-danger small" id="name-error" style="display: none;"></div>
        </div>

        <div class="mb-3">
            <label>Instructor</label>
            <input type="text" name="instructor" id="instructor" class="form-control @error('instructor') is-invalid @enderror"
                   value="{{ old('instructor', $course->instructor) }}" minlength="3" placeholder="Instructor Name (minimum 3 char, no special characters)" required>

            @error('instructor')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
            <div class="text-danger small" id="instructor-error" style="display: none;"></div>
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror"
                      rows="3" minlength="30" placeholder="Course Description (minimum 30 char)" required>{{ old('description', $course->description) }}</textarea>

            @error('description')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
            <div class="text-danger small" id="description-error" style="display: none;"></div>
        </div>

        <button type="submit" class="btn btn-primary">Update Class</button>
        <a href="{{ route('courses.index') }}" class="btn btn-secondary">Cancel</a>
    </form>

    <script>
        // Real-time validation for Courses Edit Form
        document.addEventListener('DOMContentLoaded', function() {
            const nameField = document.getElementById('name');
            const instructorField = document.getElementById('instructor');
            const descriptionField = document.getElementById('description');

            // Course Name validation
            if (nameField) {
                nameField.addEventListener('input', function() {
                    const value = this.value.trim();
                    const errorDiv = document.getElementById('name-error');

                    if (value.length > 0 && value.length < 10) {
                        errorDiv.textContent = 'Course name must be at least 10 characters long.';
                        errorDiv.style.display = 'block';
                        this.classList.add('is-invalid');
                    } else {
                        errorDiv.style.display = 'none';
                        this.classList.remove('is-invalid');
                        if (value.length >= 10) {
                            this.classList.add('is-valid');
                        }
                    }
                });
            }

            // Instructor validation
            if (instructorField) {
                instructorField.addEventListener('input', function() {
                    const value = this.value.trim();
                    const errorDiv = document.getElementById('instructor-error');
                    const instructorRegex = /^[a-zA-Z0-9\s]+$/;

                    if (value.length > 0 && value.length < 3) {
                        errorDiv.textContent = 'Instructor name must be at least 3 characters long.';
                        errorDiv.style.display = 'block';
                        this.classList.add('is-invalid');
                    } else if (value.length > 0 && !instructorRegex.test(value)) {
                        errorDiv.textContent = 'Instructor name cannot contain special characters.';
                        errorDiv.style.display = 'block';
                        this.classList.add('is-invalid');
                    } else {
                        errorDiv.style.display = 'none';
                        this.classList.remove('is-invalid');
                        if (value.length >= 3 && instructorRegex.test(value)) {
                            this.classList.add('is-valid');
                        }
                    }
                });
            }

            // Description validation
            if (descriptionField) {
                descriptionField.addEventListener('input', function() {
                    const value = this.value.trim();
                    const errorDiv = document.getElementById('description-error');

                    if (value.length > 0 && value.length < 30) {
                        errorDiv.textContent = 'Description must be at least 30 characters long.';
                        errorDiv.style.display = 'block';
                        this.classList.add('is-invalid');
                    } else {
                        errorDiv.style.display = 'none';
                        this.classList.remove('is-invalid');
                        if (value.length >= 30) {
                            this.classList.add('is-valid');
                        }
                    }
                });
            }
        });
    </script>
@endsection
