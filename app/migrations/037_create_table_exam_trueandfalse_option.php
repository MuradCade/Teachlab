<?php

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('exam_trueandfalse_option', function ($table) {
    $table->integer('optionid')->autoIncrement();
    $table->integer('questionid');
    $table->string('option_once', 255);
    $table->string('option_two', 255);
    $table->string('option_three', 255);
    $table->char('correct_option', 1);
    // $table->timestamps();

    $table->foreign('questionid')->references('questionid')->on('exam_question')->onDelete('cascade');
});
