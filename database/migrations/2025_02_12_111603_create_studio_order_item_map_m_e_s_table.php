<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudioOrderItemMapMETable extends Migration
{
    public function up()
    {
        Schema::create('StudioOrderItemMapME', function (Blueprint $table) {
            $table->increments('meorderitemmapkey'); // Primary key with auto-increment
            $table->integer('orderkey'); // Foreign key to orders
            $table->integer('ordertypeitemkey'); // Foreign key to order type items
            $table->integer('edittypekey'); // Foreign key to edit types
            $table->integer('quantity'); // Total quantity
            $table->integer('lamtypekey'); // Foreign key to lamination types
        });
    }

    public function down()
    {
        Schema::dropIfExists('StudioOrderItemMapME');
    }
}
