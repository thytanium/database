<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateThytaniumDatabaseStatesTable extends Migration
{
    /**
     * Create table.
     * 
     * @return void
     */
    public function up()
    {
        Schema::create('states', function (Blueprint $table) {
            $table->unsignedInteger('id');
            $table->string('name', 32)->index();
            $table->primary('id');
        });
    }

    /**
     * Drop table.
     * 
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('states');
    }
}
