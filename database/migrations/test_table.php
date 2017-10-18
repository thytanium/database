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
            $table->unsignedInteger('position')->nullable();
        });
    }

    public function down()
    {
        Schema::drop('test_models');
    }
}
