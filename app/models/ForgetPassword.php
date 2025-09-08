<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForgetPassword extends Model
{
    protected $table = 'forget_password';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;


    protected $fillable = [
        'userid',
        'token',
        'is_used',
        'expire_date',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(Users::class, 'userid', 'userid');
    }
}
