<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestTables extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });

        Schema::create('test_models', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('another_email')->nullable();
            $table->unsignedInteger('position')->nullable();
            $table->unsignedInteger('state_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->dateTime('last_used')->nullable();
            $table->dateTime('last_modified')->nullable();
        });

        Schema::create('position_pivot_models', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('type_1')->nullable();
            $table->string('type_2')->nullable();
            $table->unsignedInteger('position')->nullable();
        });

        Schema::create('stateful_models', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('state_id')->nullable();
        });
    }
}
