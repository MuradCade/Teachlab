<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizTrueandfalseOption extends Model
{
    protected $table = 'quiz_trueandfalse_option';
    protected $primaryKey = 'optionid';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;


    protected $fillable = [
        'questionid',
        'option_one',
        'option_two',
        'option_three',
        'correct_option',
    ];

    // Relationships
    public function question()
    {
        return $this->belongsTo(QuizQuestion::class, 'questionid', 'questionid');
    }
}
