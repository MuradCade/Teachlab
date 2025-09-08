<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quizform extends Model
{
    protected $table = 'quizform';
    protected $primaryKey = 'quizformid';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;


    protected $fillable = [
        'title',
        'content',
        'courseid',
        'userid',
        'quiztype',
        'totalquestion',
        'quizstatus',
        'created_at',
        'duration',
        'expire_date',
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

    public function questions()
    {
        return $this->hasMany(QuizQuestion::class, 'quizformid', 'quizformid');
    }

    public function studentEntries()
    {
        return $this->hasMany(QuizStudentEntry::class, 'quizformid', 'quizformid');
    }
}
