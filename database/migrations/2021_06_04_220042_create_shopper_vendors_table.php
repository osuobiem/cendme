<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopperVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shopper_vendor', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shopper_id');
            $table->unsignedBigInteger('vendor_id');
            $table->foreign('shopper_id')->references('id')->on('shoppers')->onDelete('cascade')->nullable();
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shopper_vendors');
    }
}
