<?php

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('quizform', function ($table) {
    $table->integer('quizformid')->autoIncrement();
    $table->text('title')->nullable();
    $table->text('content')->nullable();
    $table->integer('courseid');
    $table->integer('userid');
    $table->enum('quiztype', ['singlechoice', 'trueandfalse', 'directquestion']);
    $table->integer('totalquestion');
    $table->enum('quizstatus', ['active', 'disabled', 'expired']);
    $table->timestamp('created_at');
    $table->time('duration')->nullable();
    $table->date('expire_date')->nullable();
    // $table->timestamps();

    $table->foreign('courseid')->references('id')->on('course')->onDelete('cascade');
    $table->foreign('userid')->references('userid')->on('users')->onDelete('cascade');
});
