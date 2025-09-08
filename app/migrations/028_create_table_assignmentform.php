<?php

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('assignmentform', function ($table) {
    $table->integer('assignmentformid')->autoIncrement();
    $table->text('title')->nullable();
    $table->text('content')->nullable();
    $table->text('allowed_filetype');
    $table->integer('courseid');
    $table->integer('userid');
    $table->enum('status', ['active', 'disabled', 'expired']);
    $table->integer('marks');
    $table->timestamp('created_date');
    $table->dateTime('deadline_date');
    $table->timestamps();

    $table->foreign('courseid')->references('id')->on('course')->onDelete('cascade');
    $table->foreign('userid')->references('userid')->on('users')->onDelete('cascade');
});
