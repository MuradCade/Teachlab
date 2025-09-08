<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'student';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;


    protected $fillable = [
        'studentname',
        'courseid',
        'userid',
    ];

    // Relationships
    public function course()
    {
        return $this->belongsTo(Course::class, 'courseid', 'id');
    }

    public function user()
    {
        return $this->belongsTo(Users::class, 'userid', 'userid');
    }

    public function attendances()
    {
        return $this->hasMany(Attandence::class, 'studentid', 'id');
    }

    public function assignmentEntries()
    {
        return $this->hasMany(Assignmententries::class, 'studentid', 'id');
    }

    public function quizStudentEntries()
    {
        return $this->hasMany(QuizStudentEntry::class, 'studentid', 'id');
    }

    public function examStudentEntries()
    {
        return $this->hasMany(ExamStudentEntry::class, 'studentid', 'id');
    }
}
