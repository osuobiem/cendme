<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Vendor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('business_name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('address')->nullable();
            $table->float('balance')->default(0);
            $table->string('photo')->default('placeholder.png');
            $table->string('password');
            $table->json('other_details')->nullable();
            $table->integer('orders_count')->default(0);
            $table->string('qr_token')->default(0);
            $table->foreignId('area_id')->constrained()->onDelete('cascade')->onUpdate('no action');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendors');
    }
}
