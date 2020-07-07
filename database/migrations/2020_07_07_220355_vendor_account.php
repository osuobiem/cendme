<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class VendorAccount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('account_name');
            $table->integer('account_number');
            $table->foreignId('bank_id')->constrained()->onDelete('cascade')->onUpdate('no action');
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade')->onUpdate('no action');
            $table->boolean('verified')->default(false);
            $table->softDeletes();
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
        Schema::drop('vendor_accounts');
    }
}
