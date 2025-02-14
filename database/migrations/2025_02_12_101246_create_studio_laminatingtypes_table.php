<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudioLaminatingtypesTable extends Migration
{
    public function up()
    {
        Schema::create('studiolaminatingtypes', function (Blueprint $table) {
            $table->increments('lamtypekey'); // Primary key with auto-increment
            $table->text('laminatetype'); // Text field for the lamination type
            $table->decimal('unitcost', 10, 2); // Decimal field for unit cost
        });
    }

    public function down()
    {
        Schema::dropIfExists('studiolaminatingtypes');
    }
}
