<?php

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('exam_student_entry', function ($table) {
    $table->integer('id')->autoIncrement();
    $table->integer('studentid');
    $table->string('studentfullname', 255);
    $table->integer('questionid');
    $table->integer('examformid');
    $table->string('selected_answer_content', 255);
    $table->char('selected_answeras_option', 1);
    $table->timestamp('exam_entry_date');
    $table->integer('earned_student_marks')->default(0);
    // $table->timestamps();

    $table->foreign('studentid')->references('id')->on('student')->onDelete('cascade');
    $table->foreign('questionid')->references('questionid')->on('exam_question')->onDelete('cascade');
    $table->foreign('examformid')->references('examformid')->on('examform')->onDelete('cascade');
});
