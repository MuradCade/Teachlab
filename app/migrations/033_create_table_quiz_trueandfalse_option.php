<?php

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('quiz_trueandfalse_option', function ($table) {
    $table->integer('optionid')->autoIncrement();
    $table->integer('questionid')->nullable();
    $table->string('option_one', 255);
    $table->string('option_two', 255);
    $table->string('option_three', 255);
    $table->char('correct_option', 1);
    // $table->timestamps();

    $table->foreign('questionid')->references('questionid')->on('quiz_question')->onDelete('cascade');
});
