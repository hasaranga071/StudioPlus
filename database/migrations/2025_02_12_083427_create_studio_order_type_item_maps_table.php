<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudioOrderTypeItemMapTable extends Migration
{
    public function up()
    {
        Schema::create('StudioOrderTypeItemMap', function (Blueprint $table) {
            $table->increments('ordertypeitemkey'); // Primary key with auto-increment
            $table->integer('ordertypekey'); // Foreign key (optional, depending on relationships)
            $table->text('itemname'); // Text field for item name
            $table->decimal('unitprice', 8, 2); // Decimal with precision
        });
    }

    public function down()
    {
        Schema::dropIfExists('StudioOrderTypeItemMap');
    }
}
