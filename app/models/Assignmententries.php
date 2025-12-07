<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignmententries extends Model
{
    protected $table = 'assignmententries';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = true;
    protected $keyType = 'int';

    protected $fillable = [
        'studentid',
        'studentfullname',
        'courseid',
        'uploaded_filename',
        'pdf_filename',
        'assignmentformid',
        // 'submission_date',
        'uploaded_filesize',
        'marks',
    ];

    // Relationships
    // public function student()
    // {
    //     return $this->belongsTo(Student::class, 'studentid', 'id');
    // }

    public function course()
    {
        return $this->belongsTo(Course::class, 'courseid', 'id');
    }

    public function assignmentform()
    {
        return $this->belongsTo(Assignmentform::class, 'assignmentformid', 'assignmentformid');
    }
}
