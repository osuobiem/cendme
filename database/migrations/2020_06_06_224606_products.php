<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Products extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('details');
            $table->integer('quantity');
            $table->float('price');
            $table->json('other_details')->nullable();
            $table->string('photo')->default('placeholder.png');
            $table->bigInteger('sub_category_id')->unsigned();
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade')->onUpdate('no action');
            $table->foreign('sub_category_id')->references('id')->on('sub_categories')->onDelete('cascade')->onUpdate('no action');
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
        Schema::dropIfExists('products');
    }
}
