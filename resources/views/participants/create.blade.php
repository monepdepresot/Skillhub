@extends('layouts.app')

@section('content')
    <h2>Add New Participant</h2>


    <form action="{{ route('participants.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Name</label>

            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name') }}" minlength="3" placeholder="Name (minimum 3 char, no special characters)" required>

            @error('name')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
            <div class="text-danger small" id="name-error" style="display: none;"></div>
        </div>

        <div class="mb-3">
            <label>Email</label>

            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}" placeholder="Email (valid email required)" required>

            @error('email')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
            <div class="text-danger small" id="email-error" style="display: none;"></div>
        </div>

        <div class="mb-3">
            <label>Phone</label>
            <input type="tel" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror"
                   value="{{ old('phone') }}" pattern="[0-9]+" placeholder="085212341234" title="Numbers only" required>

            @error('phone')
                <div class="text-danger small" >{{ $message }}</div>
            @enderror
            <div class="text-danger small" id="phone-error" style="display: none;"></div>
        </div>

        <hr>
        <h4>Enroll in Classes</h4>
        <div class="row">
            @foreach($courses as $course)
                <div class="col-md-4 mb-2">
                    <div class="card p-2">
                        <div class="form-check">
                            
                            <input class="form-check-input" type="checkbox"
                                   name="course_ids[]"
                                   value="{{ $course->id }}"
                                   id="course_{{ $course->id }}"
                                   {{ (is_array(old('course_ids')) && in_array($course->id, old('course_ids'))) ? 'checked' : '' }}>

                            <label class="form-check-label" for="course_{{ $course->id }}">
                                <strong>{{ $course->name }}</strong><br>
                                <small>{{ $course->instructor }}</small>
                            </label>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-success mt-3">Save Participant</button>
        <a href="{{ route('participants.index') }}" class="btn btn-secondary mt-3">Cancel</a>
    </form>

    <script>
        // Real-time validation for Participants Create Form
        document.addEventListener('DOMContentLoaded', function() {
            const nameField = document.getElementById('name');
            const emailField = document.getElementById('email');
            const phoneField = document.getElementById('phone');

            // Name validation
            if (nameField) {
                nameField.addEventListener('input', function() {
                    const value = this.value.trim();
                    const errorDiv = document.getElementById('name-error');
                    const nameRegex = /^[a-zA-Z0-9\s]+$/;

                    if (value.length > 0 && value.length < 3) {
                        errorDiv.textContent = 'Name must be at least 3 characters long.';
                        errorDiv.style.display = 'block';
                        this.classList.add('is-invalid');
                    } else if (value.length > 0 && !nameRegex.test(value)) {
                        errorDiv.textContent = 'Name cannot contain special characters.';
                        errorDiv.style.display = 'block';
                        this.classList.add('is-invalid');
                    } else {
                        errorDiv.style.display = 'none';
                        this.classList.remove('is-invalid');
                        if (value.length >= 3 && nameRegex.test(value)) {
                            this.classList.add('is-valid');
                        }
                    }
                });
            }

            // Email validation
            if (emailField) {
                emailField.addEventListener('input', function() {
                    const value = this.value.trim();
                    const errorDiv = document.getElementById('email-error');
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                    if (value.length > 0 && !emailRegex.test(value)) {
                        errorDiv.textContent = 'Please enter a valid email address.';
                        errorDiv.style.display = 'block';
                        this.classList.add('is-invalid');
                    } else {
                        errorDiv.style.display = 'none';
                        this.classList.remove('is-invalid');
                        if (value.length > 0 && emailRegex.test(value)) {
                            this.classList.add('is-valid');
                        }
                    }
                });
            }

            // Phone validation
            if (phoneField) {
                phoneField.addEventListener('input', function() {
                    const value = this.value.trim();
                    const errorDiv = document.getElementById('phone-error');
                    const numericRegex = /^[0-9]+$/;

                    if (value.length > 0 && !numericRegex.test(value)) {
                        errorDiv.textContent = 'Phone number must contain numbers only.';
                        errorDiv.style.display = 'block';
                        this.classList.add('is-invalid');
                    } else {
                        errorDiv.style.display = 'none';
                        this.classList.remove('is-invalid');
                        if (value.length > 0 && numericRegex.test(value)) {
                            this.classList.add('is-valid');
                        }
                    }
                });
            }
        });
    </script>
@endsection
