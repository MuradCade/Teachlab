<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'userid';
    public $incrementing = false;
    protected $keyType = 'int';
    public $timestamps = true;


    protected $fillable = [
        'userid',
        'fullname',
        'email',
        'password',
        'role',
        'rememberme_token',
        'is_verfied'
    ];

    // Relationships
    public function subscriptionRequests()
    {
        return $this->hasMany(SubscriptionRequest::class, 'userid', 'userid');
    }

    public function userLogs()
    {
        return $this->hasMany(UserLog::class, 'userid', 'userid');
    }

    public function mailQueueu()
    {
        return $this->hasMany(MailQueueu::class, 'userid', 'userid');
    }

    public function verifyEmails()
    {
        return $this->hasMany(VerifyEmail::class, 'userid', 'userid');
    }

    public function forgetPasswords()
    {
        return $this->hasMany(ForgetPassword::class, 'userid', 'userid');
    }

    public function userSubscriptions()
    {
        return $this->hasMany(UserSubscription::class, 'userid', 'userid');
    }

    public function courses()
    {
        return $this->hasMany(Course::class, 'userid', 'userid');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'userid', 'userid');
    }

    public function attandences()
    {
        return $this->hasMany(Attandence::class, 'userid', 'userid');
    }

    public function assignmentforms()
    {
        return $this->hasMany(Assignmentform::class, 'userid', 'userid');
    }

    public function quizforms()
    {
        return $this->hasMany(Quizform::class, 'userid', 'userid');
    }

    public function examforms()
    {
        return $this->hasMany(Examform::class, 'userid', 'userid');
    }

    public function shareReportWithStudents()
    {
        return $this->hasMany(ShareReportwithStudent::class, 'userid', 'userid');
    }
}
