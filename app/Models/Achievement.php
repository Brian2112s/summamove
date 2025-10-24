<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'exercise_id',
        'user_id',
        'date',
        'start_time',
        'end_time',
        'quantity',
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the exercise associated with the achievement.
     */
    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }
}
