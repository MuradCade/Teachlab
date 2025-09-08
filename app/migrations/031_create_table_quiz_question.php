<?php

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('quiz_question', function ($table) {
    $table->integer('questionid')->autoIncrement();
    $table->text('question_content');
    $table->integer('quizformid');
    // $table->timestamps();

    $table->foreign('quizformid')->references('quizformid')->on('quizform')->onDelete('cascade');
});
