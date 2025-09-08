<?php

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('user_subscription', function ($table) {
    $table->integer('id')->autoIncrement();
    $table->integer('userid');
    $table->enum('subscription_type', ['free', 'paid'])->default('free');
    $table->enum('sub_plan', ['monthly', 'lifetime'])->default('monthly');
    $table->enum('sub_status', ['trial', 'active', 'expired', 'cancelled', 'terminated'])->default('trial');
    $table->date('sub_start_date')->nullable();
    $table->date('sub_expire_date')->nullable();
    $table->enum('is_used', ['yes', 'archive'])->nullable();
    $table->timestamps();

    $table->foreign('userid')->references('userid')->on('users')->onDelete('cascade');
});
