<?php

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('course', function ($table) {
    $table->integer('id')->autoIncrement();
    $table->string('coursename', 255);
    $table->integer('userid');
    $table->timestamp('created_at');
    // $table->timestamps();

    $table->foreign('userid')->references('userid')->on('users')->onDelete('cascade');
});
