<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    protected $table = 'quiz_question';
    protected $primaryKey = 'questionid';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;


    protected $fillable = [
        'question_content',
        'quizformid',
    ];

    // Relationships
    public function quizform()
    {
        return $this->belongsTo(Quizform::class, 'quizformid', 'quizformid');
    }

    public function singleChoiceOptions()
    {
        return $this->hasMany(QuizSinglechoiceOption::class, 'questionid', 'questionid');
    }

    public function trueFalseOptions()
    {
        return $this->hasMany(QuizTrueandfalseOption::class, 'questionid', 'questionid');
    }

    public function studentEntries()
    {
        return $this->hasMany(QuizStudentEntry::class, 'questionid', 'questionid');
    }
}
