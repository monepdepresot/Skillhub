<?php
namespace App\Http\Controllers;
use App\Models\Course;
use Illuminate\Http\Request; // For HTTP requests
use Illuminate\Support\Facades\DB; // For DB transactions for consistency
use Illuminate\Validation\Rule; // For rule ignore when updating unique field


class CourseController extends Controller
{
    // Get validation error messages
    private function getValidationMessages()
    {
        return [
            'name.required' => 'Course name is required.',
            'name.min' => 'Course name must be at least 10 characters long.',
            'name.unique' => 'A course with this name already exists.',
            'instructor.required' => 'Instructor name is required.',
            'instructor.min' => 'Instructor name must be at least 3 characters long.',
            'instructor.regex' => 'Instructor name cannot contain special characters.',
            'description.required' => 'Description is required.',
            'description.min' => 'Description must be at least 30 characters long.',
        ];
    }

    // REQUIREMENT 2.2: Display list of all courses
    // Menampilkan daftar seluruh kelas
    public function index() {
        $courses = Course::all(); // Get all courses
        return view('courses.index', compact('courses')); // Return courses.index view
    }

    // Show course create form
    public function create() {
        return view('courses.create'); // Return courses.create view
    }

    // REQUIREMENT 2.1: Add new course

    // Menambah kelas baru
    public function store(Request $request)
    {
        // Validation rules for new course data
        $validated = $request->validate([
            // Course Name: Min 10 chars
            'name' => 'required|string|min:10|unique:courses,name|max:255',

            // Instructor: Min 3 chars, no special characters
            'instructor' => [
                'required',
                'string',
                'min:3',
                'max:255',
                'regex:/^[a-zA-Z0-9\s]+$/'
            ],

            // Description: Min 30 chars
            'description' => 'required|string|min:30'
        ], $this->getValidationMessages()); // Get validation error messages

        try {
            // DB transaction to ensure data consistency (one fail, all fail)
            DB::transaction(function () use ($validated) {
                // Create new course
                Course::create($validated);
            });
            // Return success message and redirect to courses.index view
            return redirect()->route('courses.index')->with('success', 'Class created.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Failed to create class. Please try again.']);
        }
    }

    // REQUIREMENT 2.4: Update course data

    // Mengubah data kelas
    public function update(Request $request, Course $course)
    {
        // Validation rules for updating course data
        $validated = $request->validate([
            // Course Name: Min 10 chars, unique except for current course using RULE IGNORE
            'name' => [
                'required',
                'string',
                'min:10',
                Rule::unique('courses')->ignore($course->id),
                'max:255'
            ],
            // Instructor: Min 3 chars, no special characters
            'instructor' => [
                'required',
                'string',
                'min:3',
                'max:255',
                'regex:/^[a-zA-Z0-9\s]+$/'
            ],
            // Description: Min 30 chars
            'description' => 'required|string|min:30'
        ], $this->getValidationMessages()); // Get validation error messages

        try {
            // DB transaction to ensure data consistency (one fail, all fail)
            DB::transaction(function () use ($course, $validated) {
                // Update course
                $course->update($validated);
            });
            // Return success message and redirect to courses.index view
            return redirect()->route('courses.index')->with('success', 'Class updated.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Failed to update class. Please try again.']);
        }
    }

    // REQUIREMENT 2.3: Display detail of one course
    // Menampilkan detail satu kelas

    // REQUIREMENT 3.3: Display list of participants registered in a specific course
    // Menampilkan daftar peserta yang terdaftar pada suatu kelas tertentu

    public function show(Course $course) {
        return view('courses.show', compact('course')); // Return courses.show view
    }

    public function edit(Course $course) {
        return view('courses.edit', compact('course')); // Return courses.edit view
    }

    // REQUIREMENT 2.5: Delete course
    // Menghapus kelas

    // Deletion rule: Course can be deleted, which will cascade delete all participant registrations for that course (handled by database foreign key constraints)
    public function destroy(Course $course) {
        try {
            $course->delete(); // Delete course
            return redirect()->route('courses.index')->with('success', 'Class deleted successfully.'); // Return success message and redirect to courses.index view
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Could not delete class.']); // Return error message and redirect to courses.index view
        }
    }
}
