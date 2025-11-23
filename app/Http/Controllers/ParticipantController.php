<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\Course;
use Illuminate\Http\Request; // For HTTP requests
use Illuminate\Support\Facades\DB; // For DB transactions for consistency
use Illuminate\Validation\Rule; // For rule ignore when updating unique field
use Barryvdh\DomPDF\Facade\Pdf; // For PDF generation

// Handles HTTP requests for participant CRUD and course registration
class ParticipantController extends Controller
{
    // Get validation error messages
    private function getValidationMessages()
    {
        return [
            'name.required' => 'Name is required.',
            'name.min' => 'Name must be at least 3 characters long.',
            'name.regex' => 'Name cannot contain special characters.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',
            'phone.required' => 'Phone number is required.',
            'phone.numeric' => 'Phone number must contain numbers only.',
        ];
    }

    // REQUIREMENT 1.2: Display list of all participants
    // Menampilkan daftar seluruh peserta
    public function index()
    {
        // Eager loading
        //Query 1: SELECT * FROM participants
        //Query 2: SELECT * FROM courses WHERE participant_id IN (1, 2, 3... 100)
        $participants = Participant::with('courses')->get();

        // Return participants.index view
        return view('participants.index', compact('participants'));
    }

    // Show create form for new participant
    public function create()
    {
        $courses = Course::all(); // Get all courses
        return view('participants.create', compact('courses')); // Return participants.create view
    }

    // REQUIREMENT 1.1: Add new participant
    // Menambah peserta baru

    // REQUIREMENT 3.1: Record registration of one participant to one or more courses
    // Mencatat pendaftaran satu peserta ke satu atau lebih kelas

    public function store(Request $request)
    {
        // Validation rules for new participant data
        $validated = $request->validate([
            // Name: Min 3 chars, no special characters
            'name' => [
                'required',
                'string',
                'min:3',
                'regex:/^[a-zA-Z0-9\s]+$/'
            ],

            // Email: Valid email format
            'email' => [
                'required',
                'email',
                'unique:participants,email',
            ],

            // Phone: Numbers only
            'phone' => 'required|numeric',

            'course_ids' => 'nullable|array',
            'course_ids.*' => 'exists:courses,id',
        ], $this->getValidationMessages());// Get validation error messages

        try {
            DB::transaction(function () use ($validated) {
                // Create new participant
                $participant = Participant::create($validated);
                // Sync participant to selected courses updates pivot table course_participant
                $participant->courses()->sync($validated['course_ids'] ?? []);
            });
            // Return success message and redirect to participants.index view
            return redirect()->route('participants.index')
                             ->with('success', 'Participant registered successfully.');

        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Database error.']);
        }
    }

    // REQUIREMENT 1.4: Update participant data
    // Mengubah data peserta

    // REQUIREMENT 3.1: Update registration - allows changing course enrollments
    // Memperbarui pendaftaran: memungkinkan mengubah pendaftaran kelas
    public function update(Request $request, Participant $participant)
    {
        // Validation rules for updating participant data
        $validated = $request->validate([
            // Name: Min 3 chars, no special characters
            'name' => [
                'required',
                'string',
                'min:3',
                'regex:/^[a-zA-Z0-9\s]+$/'
            ],
            // Email: Valid email format, unique except for current participant using RULE IGNORE
            'email' => [
                'required',
                'email',
                Rule::unique('participants')->ignore($participant->id)
            ],
            // Phone: Numbers only
            'phone' => 'required|numeric',
            // Course IDs: Array of course IDs, nullable, and each ID must exist in courses table
            'course_ids' => 'nullable|array',
            'course_ids.*' => 'exists:courses,id',
        ], $this->getValidationMessages());// Same validation error messages as store

        try {
            DB::transaction(function () use ($participant, $validated) {
                $participant->update([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'],
                ]);

                // REQUIREMENT 3.1: Update registration - sync participant to selected courses
                // Memperbarui pendaftaran: menyinkronkan peserta ke kelas yang dipilih
                $participant->courses()->sync($validated['course_ids'] ?? []);
            });

            // Return success message and redirect to participants.index view
            return redirect()->route('participants.index')
                             ->with('success', 'Participant updated successfully.');
            // Return error message and redirect to participants.edit view
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Update failed.']);
        }
    }

    // REQUIREMENT 1.3: Display detail of one participant
    // Menampilkan detail satu peserta

    // REQUIREMENT 3.2: Display list of courses taken by a specific participant
    // Menampilkan daftar kelas yang diikuti oleh seorang peserta tertentu

    public function show(Participant $participant)
    {
        // Return participants.show detailed view
        return view('participants.show', compact('participant'));
    }

    // Show edit form for selected participant
    public function edit(Participant $participant)
    {
        $courses = Course::all(); // Get all courses
        return view('participants.edit', compact('participant', 'courses')); // Return participants.edit view
    }

    // REQUIREMENT 1.5: Delete participant
    // Menghapus peserta

    // Deletion rule: Participant can be deleted, which will cascade delete their course registrations (handled by database foreign key constraints)
    public function destroy(Participant $participant)
    {
        try {
            $participant->delete(); // Delete participant
            return redirect()->route('participants.index')
                             ->with('success', 'Participant deleted.'); // Return success message and redirect to participants.index view
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Could not delete participant.']); // Return error message and redirect to participants.index view
        }
    }

    // REQUIREMENT 3.4: Delete registration (cancel participant from a course)
    // Menghapus pendaftaran (pembatalan peserta dari kelas tertentu)
    public function detachCourse(Participant $participant, Course $course)
    {
        try {
            $participant->courses()->detach($course->id); // Detach participant from course
            return back()->with('success', 'Class registration cancelled.'); // Return success message and redirect to participants.index view
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Could not cancel class.']); // Return error message and redirect to participants.index view
        }
    }

    // Generate PDF ID Card for participant (library use)
    public function printCard(Participant $participant)
    {
        // Load View
        $pdf = Pdf::loadView('participants.pdf', compact('participant'));

        // Set landscape orientation
        $pdf->setPaper('a4', 'landscape');

        // Sanitize filename to prevent security issues
        $sanitizedName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $participant->name);
        $filename = 'ID-Card-' . $sanitizedName . '.pdf';

        // Download
        return $pdf->download($filename);
    }
}
