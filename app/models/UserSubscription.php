<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{
    protected $table = 'user_subscription';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;


    protected $fillable = [
        'userid',
        'subscription_type',
        'sub_plan',
        'sub_status',
        'sub_start_date',
        'sub_expire_date',
        'is_used',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(Users::class, 'userid', 'userid');
    }
}
