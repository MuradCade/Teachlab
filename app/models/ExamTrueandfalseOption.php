<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamTrueandfalseOption extends Model
{
    protected $table = 'exam_trueandfalse_option';
    protected $primaryKey = 'optionid';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;


    protected $fillable = [
        'questionid',
        'option_once',
        'option_two',
        'option_three',
        'correct_option',
    ];

    // Relationships
    public function question()
    {
        return $this->belongsTo(ExamQuestion::class, 'questionid', 'questionid');
    }
}
