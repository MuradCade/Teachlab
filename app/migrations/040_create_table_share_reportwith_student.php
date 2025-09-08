<?php

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('share_reportwith_student', function ($table) {
    $table->integer('id')->autoIncrement();
    $table->integer('studenid');
    $table->integer('courseid');
    $table->integer('userid');
    $table->enum('sharing_resource', ['attadence', 'assignment', 'quiz', 'exam', 'all']);
    $table->timestamp('created_at');
    // $table->timestamps();

    $table->foreign('studenid')->references('id')->on('student')->onDelete('cascade');
    $table->foreign('courseid')->references('id')->on('course')->onDelete('cascade');
    $table->foreign('userid')->references('userid')->on('users')->onDelete('cascade');
});
