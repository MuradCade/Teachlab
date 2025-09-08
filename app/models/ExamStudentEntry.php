<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamStudentEntry extends Model
{
    protected $table = 'exam_student_entry';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;


    protected $fillable = [
        'studentid',
        'studentfullname',
        'questionid',
        'examformid',
        'selected_answer_content',
        'selected_answeras_option',
        'exam_entry_date',
        'earned_student_marks',
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class, 'studentid', 'id');
    }

    public function question()
    {
        return $this->belongsTo(ExamQuestion::class, 'questionid', 'questionid');
    }

    public function examform()
    {
        return $this->belongsTo(Examform::class, 'examformid', 'examformid');
    }
}
