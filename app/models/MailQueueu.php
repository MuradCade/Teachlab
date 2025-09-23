<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MailQueueu extends Model
{
    protected $table = 'mail_queueu';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;


    protected $fillable = [
        'subject',
        'body',
        'username',
        'useremail',
        'userid',
        'mail_type',
        'mail_status',
        'mail_error'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(Users::class, 'userid', 'userid');
    }
}
