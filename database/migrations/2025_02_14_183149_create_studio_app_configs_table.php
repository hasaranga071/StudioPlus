<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('studioappconfig', function (Blueprint $table) {
            $table->increments('appconfigkey'); // Primary key with auto-increment
            $table->integer('studiokey');
            $table->decimal('softcopyunitprice');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('studioappconfig');
    }
};
