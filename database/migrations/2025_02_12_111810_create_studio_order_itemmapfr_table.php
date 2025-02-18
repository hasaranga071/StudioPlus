<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudioOrderItemMapFRTable extends Migration
{
    public function up()
    {
        Schema::create('studioorderitemmapfr', function (Blueprint $table) {
            $table->increments('frorderitemmapkey'); // Primary key with auto-increment
            $table->integer('orderkey'); // Foreign key to orders
            $table->integer('framesizekey'); // Foreign key to frame sizes
            $table->integer('edittypekey'); // Foreign key to edit types
            $table->integer('quantity'); // Total quantity
            $table->integer('lamtypekey'); // Foreign key to lamination types
        });
    }

    public function down()
    {
        Schema::dropIfExists('studioorderitemmapfr');
    }
}
