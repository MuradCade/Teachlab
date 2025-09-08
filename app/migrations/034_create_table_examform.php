<?php

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('examform', function ($table) {
    $table->integer('examformid')->autoIncrement();
    $table->text('title')->nullable();
    $table->text('content')->nullable();
    $table->integer('courseid');
    $table->integer('userid');
    $table->enum('examtype', ['singlechoice', 'trueandfalse', 'directquestion']);
    $table->integer('totalquestion');
    $table->enum('examstatus', ['active', 'disabled', 'expired']);
    $table->timestamp('created_at');
    $table->time('duration');
    $table->date('expire_date');
    // $table->timestamps();

    $table->foreign('courseid')->references('id')->on('course')->onDelete('cascade');
    $table->foreign('userid')->references('userid')->on('users')->onDelete('cascade');
});
