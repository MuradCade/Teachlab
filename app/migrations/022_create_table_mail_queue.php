<?php

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('mail_queueu', function ($table) {
    $table->integer('id')->autoIncrement();
    $table->text('subject');
    $table->text('body');
    $table->string('username', 255)->nullable();
    $table->string('useremail', 255)->nullable();
    $table->integer('userid');
    $table->enum('mail_type', ['signup', 'recover_account']);
    $table->enum('mail_status', ['pending', 'processing', 'sent', 'error'])->default('pending');
    $table->text('mail_error')->nullable();
    $table->timestamps();

    $table->foreign('userid')->references('userid')->on('users')->onDelete('cascade');
});
