<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentSurveyResponse extends Model
{
    use HasFactory;

    protected $table = 'student_survey_responses';

    protected $fillable = [
        'school_id',
        'student_name',
        'class_grade',
        'score',
        'ip_address',
        'device_token',
    ];

    /**
     * Relasi ke sekolah penerima survei.
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Relasi ke rincian jawaban per pertanyaan.
     */
    public function answers()
    {
        return $this->hasMany(StudentSurveyAnswer::class, 'response_id');
    }
}
