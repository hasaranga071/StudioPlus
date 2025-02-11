<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudiousersTable extends Migration
{
    public function up()
    {
        Schema::create('studiousers', function (Blueprint $table) {
            $table->integer('userkey')->primary(); // Custom primary key
            $table->integer('studiokey')->nullable();
            $table->string('username')->nullable();
            $table->integer('usertypekey')->nullable();
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->integer('rolekey')->nullable();
            $table->string('phonenumber')->nullable();
            $table->string('address')->nullable();
            $table->boolean('isactive')->nullable();
            $table->string('profileimage')->nullable();
            $table->timestamp('createdtime')->useCurrent();
            $table->timestamp('updatedtime')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down()
    {
        Schema::dropIfExists('studiousers');
    }
}
