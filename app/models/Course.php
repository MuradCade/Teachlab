<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $table = 'course';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;


    protected $fillable = [
        'coursename',
        'userid',
        'created_at',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(Users::class, 'userid', 'userid');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'courseid', 'id');
    }

    public function attendances()
    {
        return $this->hasMany(Attandence::class, 'courseid', 'id');
    }

    public function assignmentforms()
    {
        return $this->hasMany(Assignmentform::class, 'courseid', 'id');
    }

    public function quizforms()
    {
        return $this->hasMany(Quizform::class, 'courseid', 'id');
    }

    public function examforms()
    {
        return $this->hasMany(Examform::class, 'courseid', 'id');
    }

    public function sharedReports()
    {
        return $this->hasMany(ShareReportwithStudent::class, 'courseid', 'id');
    }
}
