<?php

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('attandence', function ($table) {
    $table->integer('id')->autoIncrement();
    $table->integer('studentid');
    $table->integer('courseid');
    $table->integer('userid');
    $table->integer('attandence_marks');
    $table->timestamp('date');
    $table->smallInteger('present')->default(0);
    $table->smallInteger('absent')->default(0);
    $table->timestamps();

    $table->foreign('studentid')->references('id')->on('student')->onDelete('cascade');
    $table->foreign('courseid')->references('id')->on('course')->onDelete('cascade');
    $table->foreign('userid')->references('userid')->on('users')->onDelete('cascade');
});
