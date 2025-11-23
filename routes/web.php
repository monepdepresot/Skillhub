<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ParticipantController;

Route::get('/', function () {
    return redirect()->route('participants.index');
});

// REQUIREMENT 2: Course Management
// Manajemen Data Kelas

// Provides: index, create, store, show, edit, update, destroy
Route::resource('courses', CourseController::class);

// REQUIREMENT 1: Participant Management
// Manajemen Data Peserta

// Provides: index, create, store, show, edit, update, destroy
Route::resource('participants', ParticipantController::class);

// REQUIREMENT 3.4: Delete registration route
// Menghapus pendaftaran (pembatalan peserta dari kelas tertentu)
Route::delete('participants/{participant}/courses/{course}', [ParticipantController::class, 'detachCourse'])
    ->name('participants.detach');

// Additional feature: PDF ID Card generation (external)
Route::get('participants/{participant}/print', [ParticipantController::class, 'printCard'])
    ->name('participants.print');
