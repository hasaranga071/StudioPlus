<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudioFrametypesTable extends Migration
{
    public function up()
    {
        Schema::create('studioframetypes', function (Blueprint $table) {
            $table->increments('frametypekey'); // Primary key with auto-increment
            $table->text('frametype'); // Text field for frame type
        });
    }

    public function down()
    {
        Schema::dropIfExists('studioframetypes');
    }
}
