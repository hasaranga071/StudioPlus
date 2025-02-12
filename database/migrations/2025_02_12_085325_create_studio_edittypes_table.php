<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudioEdittypesTable extends Migration
{
    public function up()
    {
        Schema::create('StudioEdittypes', function (Blueprint $table) {
            $table->increments('edittypekey'); // Primary key with auto-increment
            $table->text('edittype'); // Text field for edit type
            $table->decimal('unitcost', 10, 2); // Decimal field for unit cost
        });
    }

    public function down()
    {
        Schema::dropIfExists('StudioEdittypes');
    }
}
