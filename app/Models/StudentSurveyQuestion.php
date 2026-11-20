<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentSurveyQuestion extends Model
{
    use HasFactory;

    protected $table = 'student_survey_questions';

    protected $fillable = [
        'indicator',
        'question_text',
        'order',
    ];

    /**
     * Relasi ke jawaban survei siswa.
     */
    public function answers()
    {
        return $this->hasMany(StudentSurveyAnswer::class, 'question_id');
    }
}
