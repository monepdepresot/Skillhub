<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory; // For factory creation
    protected $fillable = [
        'name',
        'email',
        'phone'
    ];

    // REQUIREMENT 3: Many-to-Many relationship with Course
    // A participant can enroll in multiple courses (one-to-many)

    // Relasi many-to-many dengan Course: seorang peserta dapat mengikuti lebih dari satu kelas
    public function courses()
    {
        // Many-to-Many relationship with Course and tracks timestamps 
        return $this->belongsToMany(Course::class, 'course_participant')->withTimestamps();
    }
}
