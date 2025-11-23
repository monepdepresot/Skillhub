<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // REQUIREMENT 3: Pivot table for Many-to-Many relationship

    // This table stores participant-course registrations, allowing:
    // - One participant to enroll in multiple courses
    // - One course to have multiple participants


    public function up(): void
    {
        Schema::create('course_participant', function (Blueprint $table) {
            $table->id();

            // Relasi: Peserta mengambil Kelas
            $table->foreignId('participant_id') // Participant ID
                ->constrained('participants') // Constrained to participants table
                ->onDelete('cascade'); // If a participant is deleted, all related row (participant in course) will be deleted

            // Relasi: Kelas diambil Peserta
            $table->foreignId('course_id') // Course ID
                ->constrained('courses') // Constrained to courses table
                ->onDelete('cascade'); // If a course is deleted, all related row (course joined by participant) will be deleted

            $table->timestamps(); // Created at and updated at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_participant');
    }
};
