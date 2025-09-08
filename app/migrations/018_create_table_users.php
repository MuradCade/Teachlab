<?php

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('users', function ($table) {
    $table->integer('userid')->primary();
    $table->string('fullname', 255);
    $table->string('email', 255)->unique();
    $table->text('password');
    $table->enum('role', ['teacher', 'university', 'admin']);
    $table->text('rememberme_token')->nullable();
    $table->smallInteger('is_verfied')->default(0);
    $table->timestamps();
});
