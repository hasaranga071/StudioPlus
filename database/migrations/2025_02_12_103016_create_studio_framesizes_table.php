<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudioFramesizesTable extends Migration
{
    public function up()
    {
        Schema::create('StudioFramesizes', function (Blueprint $table) {
            $table->increments('framesizekey'); // Primary key with auto-increment
            $table->integer('ordertypeitemkey'); // Foreign key to order type item
            $table->integer('frametypekey'); // Foreign key to frame type
            $table->text('size'); // Text field for size
            $table->decimal('unitprice', 10, 2); // Decimal field for unit price
        });
    }

    public function down()
    {
        Schema::dropIfExists('StudioFramesizes');
    }
}
