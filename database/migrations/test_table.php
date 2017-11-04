<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestTables extends Migration
{
    public function up()
    {
        Schema::create('test_models', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('another_email')->nullable();
            $table->unsignedInteger('position')->nullable();
            $table->unsignedInteger('state_id')->nullable();
        });

        Schema::create('stateful_models', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('state_id')->nullable();
        });
    }
}
