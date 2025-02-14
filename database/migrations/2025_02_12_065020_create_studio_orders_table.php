<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('studioorders', function (Blueprint $table) {
            $table->id('orderkey');
            $table->string('orderid')->unique();
            $table->unsignedBigInteger('studiokey');
            $table->unsignedBigInteger('ordertypekey');
            $table->unsignedBigInteger('customerkey');
            $table->boolean('isurgent')->default(0);
            $table->unsignedBigInteger('updateduserkey')->nullable();
            $table->unsignedBigInteger('createduserkey')->nullable();
            $table->decimal('totalcost', 10, 2)->default(0.00);
            $table->decimal('paidcost', 10, 2)->default(0.00);
            $table->integer('discount')->default(0);
            $table->string('salestatus', 255)->default('pending');
            $table->timestamp('createdtime')->useCurrent();
            $table->timestamp('updatedtime')->useCurrent()->useCurrentOnUpdate();
            $table->timestamp('deliverydate')->nullable();
            $table->string('remarks', 255)->nullable();

            // Foreign Key Constraints
            $table->foreign('studiokey')->references('studiokey')->on('studios')->onDelete('cascade');
            $table->foreign('ordertypekey')->references('ordertypekey')->on('studioordertypes')->onDelete('cascade');
            $table->foreign('customerkey')->references('customerkey')->on('studiocustomers')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('studioorders');
    }
};
