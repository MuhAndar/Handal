<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpeedCodingParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'batch_name',
        'kualifikasi_rank',
        'final_rank',
        'name',
        'school',
        'kelas',
        'jenjang',
        'kualifikasi_time',
        'kualifikasi_score',
        'final_time',
        'final_score',
        'is_finalist',
        'is_winner',
        'badge',
        'status',
        'language',
        'avatar',
    ];

    protected $casts = [
        'kualifikasi_rank' => 'integer',
        'final_rank' => 'integer',
        'kualifikasi_score' => 'float',
        'final_score' => 'float',
        'is_finalist' => 'boolean',
        'is_winner' => 'boolean',
    ];

    /**
     * Otomatis set avatar inisial (misal: "Ahmad Zaki" -> "AZ") jika belum ada
     */
    public static function generateAvatar(string $name): string
    {
        $words = preg_split('/\s+/', trim($name));
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }
        return strtoupper(substr($name, 0, 2));
    }
}
