<?php

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('verify_email', function ($table) {
    $table->integer('id')->autoIncrement();
    $table->text('token');
    $table->integer('userid');
    $table->smallInteger('is_used')->default(0);
    $table->timestamp('date');
    $table->timestamps();

    $table->foreign('userid')->references('userid')->on('users')->onDelete('cascade');
});
