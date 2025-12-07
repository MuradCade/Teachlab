<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignmentform extends Model
{
    protected $table = "assignmentform";
    protected $primaryKey = "assignmentformid";
    public $incrementing = true;
    protected $keyType = "int";
    public $timestamps = true;

    protected $fillable = [
        "title",
        "content",
        "allowed_filetype",
        "courseid",
        "userid",
        "status",
        "marks",
        "deadline_date",
    ];
    // Important: define casts for date columns
    // protected $casts = [
    //     'deadline_date' => 'string', // use 'date' for DATE columns
    // ];

    // Relationships
    public function course()
    {
        return $this->belongsTo(Course::class, "courseid", "id");
    }

    public function user()
    {
        return $this->belongsTo(Users::class, "userid", "userid");
    }

    public function assignmentEntries()
    {
        return $this->hasMany(
            Assignmententries::class,
            "assignmentformid",
            "assignmentformid",
        );
    }
}
