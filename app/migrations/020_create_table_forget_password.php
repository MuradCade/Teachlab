<?php

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('forget_password', function ($table) {
    $table->integer('id')->autoIncrement();
    $table->integer('userid');
    $table->text('token');
    $table->smallInteger('is_used')->default(0);
    $table->dateTime('expire_date')->nullable();
    $table->timestamps();

    $table->foreign('userid')->references('userid')->on('users')->onDelete('cascade');
});
