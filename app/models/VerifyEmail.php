<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerifyEmail extends Model
{
    protected $table = 'verify_email';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;


    protected $fillable = [
        'token',
        'userid',
        'is_used',
        'date',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(Users::class, 'userid', 'userid');
    }
}
