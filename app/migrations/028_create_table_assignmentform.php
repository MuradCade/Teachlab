<?php

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('assignmentform', function ($table) {
    $table->integer('assignmentformid')->autoIncrement();
    $table->text('title')->nullable();
    $table->text('content')->nullable();
    $table->text('allowed_filetype')->nullable();
    $table->integer('courseid');
    $table->integer('userid');
    $table->enum('status', ['published', 'disabled', 'draft']);
    $table->integer('marks');
    $table->dateTime('deadline_date')->nullable();
    $table->timestamps();

    $table->foreign('courseid')->references('id')->on('course')->onDelete('cascade');
    $table->foreign('userid')->references('userid')->on('users')->onDelete('cascade');
});
