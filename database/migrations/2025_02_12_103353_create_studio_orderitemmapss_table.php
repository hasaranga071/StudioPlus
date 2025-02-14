<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudioOrderItemMapSSTable extends Migration
{
    public function up()
    {
        Schema::create('studioorderitemmapss', function (Blueprint $table) {
            $table->increments('ssorderitemmapkey'); // Primary key with auto-increment
            $table->integer('orderkey'); // Foreign key to the orders table
            $table->integer('ordertypeitemkey'); // Foreign key to order type item
            $table->integer('edittypekey'); // Foreign key to edit types
            $table->integer('lamtypekey'); // Foreign key to lamination types
            $table->integer('quantity'); // Total quantity
            $table->integer('softcopyquantity'); // Soft copy quantity
            $table->integer('hardcopyquantity'); // Hard copy quantity
            $table->decimal('totalcost'); // total cost
        });
    }

    public function down()
    {
        Schema::dropIfExists('studioorderitemmapss');
    }
}
