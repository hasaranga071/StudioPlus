<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudioCustomersTable extends Migration
{
    public function up()
    {
        Schema::create('studiocustomers', function (Blueprint $table) {
            $table->id('customerkey'); // Primary key
            $table->unsignedBigInteger('studiokey'); // Foreign key
            $table->string('username');
            $table->string('email')->unique();
            $table->string('phonenumber');
            $table->text('address')->nullable();
            $table->timestamp('createdtime')->useCurrent();

            // Add foreign key constraint
            //$table->foreign('studiokey')->references('studiokey')->on('studios')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('studiocustomers');
    }
}
