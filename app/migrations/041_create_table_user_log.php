<?php

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('user_log', function ($table) {
    $table->integer('id')->autoIncrement();
    $table->integer('userid');
    $table->text('activity_type');
    $table->timestamp('date')->nullable();
    // $table->timestamps();

    $table->foreign('userid')->references('userid')->on('users')->onDelete('cascade');
});
