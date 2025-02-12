<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudioOrderItemMapECTable extends Migration
{
    public function up()
    {
        Schema::create('StudioOrderItemMapEC', function (Blueprint $table) {
            $table->increments('ecorderitemmapkey'); // Primary key with auto-increment
            $table->integer('orderkey'); // Foreign key to orders
            $table->integer('originalorderkey'); // Foreign key to original orders
            $table->integer('ordertypeitemkey'); // Foreign key to order type items
            $table->integer('edittypekey'); // Foreign key to edit types
            $table->integer('lamtypekey'); // Foreign key to lamination types
            $table->integer('quantity'); // Quantity of items
        });
    }

    public function down()
    {
        Schema::dropIfExists('StudioOrderItemMapEC');
    }
}
