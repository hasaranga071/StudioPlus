<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('studios', function (Blueprint $table) {
            $table->id('studiokey'); // Primary key
            $table->string('studioname'); // Studio name
            $table->unsignedBigInteger('userkey'); // Foreign key for StudioUsers
            $table->text('description')->nullable(); // Description
            $table->text('address')->nullable(); // Address
            $table->text('location')->nullable(); // Location
            $table->integer('isactive')->default(1); // Active status (1 = active, 0 = inactive)
            $table->timestamp('createdtime')->useCurrent(); // Creation time
            $table->timestamp('updatedtime')->useCurrent()->nullable(); // Update time

            // Foreign key constraint
            $table->foreign('userkey')
                  ->references('userkey')
                  ->on('studio_users') // Make sure the StudioUsers table exists
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('studios');
    }
}
