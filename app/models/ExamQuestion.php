<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamQuestion extends Model
{
    protected $table = 'exam_question';
    protected $primaryKey = 'questionid';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;


    protected $fillable = [
        'question_content',
        'examformid',
    ];

    // Relationships
    public function examform()
    {
        return $this->belongsTo(Examform::class, 'examformid', 'examformid');
    }

    public function singleChoiceOptions()
    {
        return $this->hasMany(ExamSinglechoiceOption::class, 'questionid', 'questionid');
    }

    public function trueFalseOptions()
    {
        return $this->hasMany(ExamTrueandfalseOption::class, 'questionid', 'questionid');
    }

    public function studentEntries()
    {
        return $this->hasMany(ExamStudentEntry::class, 'questionid', 'questionid');
    }
}
