<?php


use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('migrations', function ($table) {
    $table->increments('id');
    $table->string('migration')->unique(); // Filename of the migration
    $table->timestamp('executed_at')->default(Capsule::raw('CURRENT_TIMESTAMP'));
});
