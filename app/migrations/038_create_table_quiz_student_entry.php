<?php

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('quiz_student_entry', function ($table) {
    $table->integer('id')->autoIncrement();
    $table->integer('studentid');
    $table->string('studentfullname', 255);
    $table->integer('questionid');
    $table->integer('quizformid');
    $table->string('selected_answer_content', 255);
    $table->char('selected_answeras_option', 1);
    $table->timestamp('quiz_entry_date');
    $table->integer('earned_student_marks')->default(0);
    // $table->timestamps();

    $table->foreign('studentid')->references('id')->on('student')->onDelete('cascade');
    $table->foreign('questionid')->references('questionid')->on('quiz_question')->onDelete('cascade');
    $table->foreign('quizformid')->references('quizformid')->on('quizform')->onDelete('cascade');
});
