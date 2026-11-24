<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpeedCodingBatch extends Model
{
    protected $fillable = [
        'year',
        'batch_name',
        'title',
        'subtitle',
        'date',
        'location',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];
}
