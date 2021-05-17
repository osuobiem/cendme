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
            $table->integer('quantity')->default(0);
            $table->float('price');
            $table->text('other_details')->nullable();
            $table->string('photo')->default('placeholder.png');
            $table->boolean('status')->default(true);
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade')->onUpdate('no action');
            $table->foreignId('subcategory_id')->constrained()->onDelete('cascade')->onUpdate('no action');
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
