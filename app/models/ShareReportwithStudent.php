<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShareReportwithStudent extends Model
{
    protected $table = 'share_reportwith_student';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;


    protected $fillable = [
        'studenid',
        'courseid',
        'userid',
        'sharing_resource',
        'created_at',
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class, 'studenid', 'id');
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
