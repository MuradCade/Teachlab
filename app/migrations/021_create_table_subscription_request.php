<?php

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('subscription_request', function ($table) {
    $table->integer('id')->autoIncrement();
    $table->integer('userid');
    $table->integer('username')->nullable();
    $table->string('userphone', 255)->nullable();
    $table->string('sub_plan', 255);
    $table->enum('payment_method', ['zaad', 'edahab', 'bank transfer']);
    $table->string('sub_amount', 255);
    $table->date('started_date');
    $table->enum('sub_status', ['pending', 'approved', 'terminated', 'cancelled']);
    $table->timestamps();

    $table->foreign('userid')->references('userid')->on('users')->onDelete('cascade');
});
