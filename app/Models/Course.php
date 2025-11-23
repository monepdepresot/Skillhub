<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'instructor'
    ];

    // REQUIREMENT 3: Many-to-Many relationship with Participant
    // A course can have multiple participants (one-to-many)

    // Relasi many-to-many dengan Participant: satu kelas dapat diikuti oleh banyak peserta
    public function participants()
    {
        // Many-to-Many relationship with Participant and tracks timestamps 
        return $this->belongsToMany(Participant::class, 'course_participant')
                    ->withTimestamps();
    }
}
