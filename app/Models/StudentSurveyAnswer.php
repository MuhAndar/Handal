<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentSurveyAnswer extends Model
{
    use HasFactory;

    protected $table = 'student_survey_answers';

    protected $fillable = [
        'response_id',
        'question_id',
        'answer_value',
    ];

    /**
     * Relasi ke respon utama siswa.
     */
    public function response()
    {
        return $this->belongsTo(StudentSurveyResponse::class, 'response_id');
    }

    /**
     * Relasi ke butir pertanyaan.
     */
    public function question()
    {
        return $this->belongsTo(StudentSurveyQuestion::class, 'question_id');
    }
}
