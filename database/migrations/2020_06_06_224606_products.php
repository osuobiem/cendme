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
            $table->boolean('status')->default(true);
            $table->bigInteger('subcategory_id')->unsigned();
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade')->onUpdate('no action');
            $table->foreign('subcategory_id')->references('id')->on('subcategories')->onDelete('cascade')->onUpdate('no action');
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
        Schema::dropIfExists('products');
    }
}
