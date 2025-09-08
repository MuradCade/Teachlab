<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attandence extends Model
{
    protected $table = 'attandence';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;


    protected $fillable = [
        'studentid',
        'courseid',
        'userid',
        'attandence_marks',
        'date',
        'present',
        'absent',
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class, 'studentid', 'id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'courseid', 'id');
    }

    public function user()
    {
        return $this->belongsTo(Users::class, 'userid', 'userid');
    }
}
