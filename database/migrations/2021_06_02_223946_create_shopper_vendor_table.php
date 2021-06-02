<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopperVendorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shopper_vendor', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('shopper_id');
            $table->unsignedInteger('vendor_id');
            $table->foreign('shopper_id')->references('id')->on('shopper')->onDelete('cascade');
            $table->foreign('vendor_id')->references('id')->on('vendor')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shopper_vendor', function (Blueprint $table) {
            //
        });
    }
}
