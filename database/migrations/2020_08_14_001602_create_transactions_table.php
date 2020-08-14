<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('reference');
            $table->string('amount');
            $table->integer('status')->default(0);
            $table->string('type');
            $table->foreignId('order_id')->nullable()->constrained()->onUpdate('no action')->onDelete('cascade');
            $table->foreignId('shopper_id')->nullable()->constrained()->onUpdate('no action')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onUpdate('no action')->onDelete('cascade');
            $table->foreignId('vendor_id')->nullable()->constrained()->onUpdate('no action')->onDelete('cascade');
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
        Schema::dropIfExists('transactions');
    }
}
