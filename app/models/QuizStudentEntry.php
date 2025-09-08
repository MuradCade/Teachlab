<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizStudentEntry extends Model
{
    protected $table = 'quiz_student_entry';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;


    protected $fillable = [
        'studentid',
        'studentfullname',
        'questionid',
        'quizformid',
        'selected_answer_content',
        'selected_answeras_option',
        'quiz_entry_date',
        'earned_student_marks',
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class, 'studentid', 'id');
    }

    public function question()
    {
        return $this->belongsTo(QuizQuestion::class, 'questionid', 'questionid');
    }

    public function quizform()
    {
        return $this->belongsTo(Quizform::class, 'quizformid', 'quizformid');
    }
}
