<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_name',
        'task',
        'priority',
        'mood_tag',
        'completed',
        'timer_start',
        'timer_duration'
    ];
}
