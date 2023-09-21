<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;
    protected $fillable = [
        'credit_hours',
        'assistant_name',
        'room_name',
        'room',
        'class',
        'class_hours',
        'class_credit_hours',
        'to', 
        'from',
        'reference_number',
        'last_update',
        'subject_status',
        'subject_name',
        'subject',
        'time',
        'day',
        'student_name',
        'student_id',
        'training_program',
        'training_department',
        'level',
        'training_place',
        'training_semester',
    ];
}
