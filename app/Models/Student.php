<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    /** @use HasFactory<\Database\Factories\StudentFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'student_code',
        'first_name',
        'date_of_birth',
        'school_code',
    ];

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'student_id';

    protected $autoIncrement = false;

    /**
     * Get the school that owns the student.
     */
    public function school()
    {
        return $this->belongsTo(School::class, 'school_code', 'school_code');
    }
}
