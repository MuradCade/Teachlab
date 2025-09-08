<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Examform extends Model
{
    protected $table = 'examform';
    protected $primaryKey = 'examformid';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;


    protected $fillable = [
        'title',
        'content',
        'courseid',
        'userid',
        'examtype',
        'totalquestion',
        'examstatus',
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
        return $this->hasMany(ExamQuestion::class, 'examformid', 'examformid');
    }

    public function studentEntries()
    {
        return $this->hasMany(ExamStudentEntry::class, 'examformid', 'examformid');
    }
}
