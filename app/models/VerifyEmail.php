<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerifyEmail extends Model
{
    protected $table = 'verify_email';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;


    protected $fillable = [
        'userid',
        'token',
        'is_used',
        // 'date', this attribute is automatically managed by database (its timestamp)
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(Users::class, 'userid', 'userid');
    }
}
