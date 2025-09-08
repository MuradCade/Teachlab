<?php

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('exam_question', function ($table) {
    $table->integer('questionid')->autoIncrement();
    $table->text('question_content');
    $table->integer('examformid')->nullable();
    // $table->timestamps();

    $table->foreign('examformid')->references('examformid')->on('examform')->onDelete('cascade');
});
