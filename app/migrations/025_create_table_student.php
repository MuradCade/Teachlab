<?php

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('student', function ($table) {
    $table->integer('id')->primary();
    $table->text('studentname');
    $table->integer('courseid');
    $table->integer('userid');
    $table->timestamps();

    $table->foreign('courseid')->references('id')->on('course')->onDelete('cascade');
    $table->foreign('userid')->references('userid')->on('users')->onDelete('cascade');
});
