<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Agents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->string('gender')->nullable();
            $table->timestamp('dob');
            $table->timestamp('bvn')->nullable();
            $table->text('about')->nullable();
            $table->text('address')->nullable();
            $table->float('balance')->default(0);
            $table->string('password');
            $table->json('other_details')->nullable();
            $table->string('photo')->default('placeholder.png');
            $table->boolean('verified')->default(false);
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
        Schema::dropIfExists('agents');
    }
}
