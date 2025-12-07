<?php

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('assignmententries', function ($table) {
    $table->integer('id')->autoIncrement();
    $table->integer('studentid');
    $table->string('studentfullname', 255);
    $table->integer('courseid');
    $table->string('uploaded_filename', 255);
    $table->string('pdf_filename', 255);
    $table->integer('assignmentformid');
    $table->timestamp('submission_date');
    $table->string('uploaded_filesize', 255);
    $table->integer('marks');
    $table->timestamps();

    $table->foreign('studentid')->references('id')->on('student')->onDelete('cascade');
    $table->foreign('courseid')->references('id')->on('course')->onDelete('cascade');
    $table->foreign('assignmentformid')->references('assignmentformid')->on('assignmentform')->onDelete('cascade');
});
