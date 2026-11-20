<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class School extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'address',
        'jenjang',
        'npsn',
        'survey_code',
        'is_student_survey_open',
        'self_assessment_score',
        'student_survey_score',
        'student_respondents_count',
        'current_score',
    ];

    protected $casts = [
        'is_student_survey_open' => 'boolean',
        'self_assessment_score' => 'float',
        'student_survey_score' => 'float',
        'current_score' => 'float',
        'student_respondents_count' => 'integer',
    ];

    /**
     * Boot model untuk memastikan setiap sekolah memiliki kode unik survei siswa.
     */
    protected static function booted()
    {
        static::creating(function ($school) {
            if (empty($school->survey_code)) {
                $school->survey_code = Str::lower(Str::random(8));
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function surveys()
    {
        return $this->hasMany(Survey::class);
    }

    public function studentResponses()
    {
        return $this->hasMany(StudentSurveyResponse::class);
    }

    /**
     * Hitung ulang skor gabungan (70% Asesmen Mandiri + 30% Survei Siswa).
     */
    public function recalculateOverallScore()
    {
        // Hitung rata-rata survei siswa jika ada respon
        $avgStudentScore = $this->studentResponses()->avg('score');
        $respondentsCount = $this->studentResponses()->count();

        $this->student_survey_score = $avgStudentScore ? round($avgStudentScore, 2) : 0;
        $this->student_respondents_count = $respondentsCount;

        // Ambil skor asesmen mandiri terbaru dari survey sekolah (jika ada)
        $latestSurvey = $this->surveys()->whereIn('status', ['submitted', 'verified'])->latest()->first();
        if ($latestSurvey) {
            $this->self_assessment_score = round($latestSurvey->total_score, 2);
        }

        // Formula: 70% Asesmen Mandiri + 30% Survei Siswa
        if ($this->student_respondents_count > 0) {
            $finalScore = (0.70 * $this->self_assessment_score) + (0.30 * $this->student_survey_score);
        } else {
            // Jika belum ada siswa yang mengisi, gunakan skor asesmen mandiri sebagai nilai sementara
            $finalScore = $this->self_assessment_score;
        }

        $this->current_score = round($finalScore, 2);
        $this->save();

        return $this->current_score;
    }

    /**
     * Label predikat / tier kesiapan digital sekolah.
     */
    public function getRankLabelAttribute()
    {
        $score = $this->current_score;

        if ($score >= 86) return 'Sekolah Unggul Digital';
        if ($score >= 76) return 'Sekolah Maju';
        if ($score >= 51) return 'Sekolah Berkembang';
        if ($score >= 31) return 'Sekolah Pemula';
        return 'Perlu Pendampingan';
    }

    /**
     * Kode warna badge tier.
     */
    public function getRankColorAttribute()
    {
        $score = $this->current_score;

        if ($score >= 86) return 'bg-cyan-100 text-cyan-800 border-cyan-300';
        if ($score >= 76) return 'bg-blue-100 text-blue-800 border-blue-300';
        if ($score >= 51) return 'bg-amber-100 text-amber-800 border-amber-300';
        if ($score >= 31) return 'bg-slate-100 text-slate-700 border-slate-300';
        return 'bg-rose-100 text-rose-800 border-rose-300';
    }

    /**
     * Ikon badge tier.
     */
    public function getRankIconAttribute()
    {
        $score = $this->current_score;

        if ($score >= 86) return 'bi-gem';
        if ($score >= 76) return 'bi-trophy-fill';
        if ($score >= 51) return 'bi-award-fill';
        if ($score >= 31) return 'bi-star-fill';
        return 'bi-exclamation-triangle-fill';
    }
}
