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
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('email')->unique();
            $table->string('phone')->unique()->nullable();
            $table->string('gender')->nullable();
            $table->timestamp('dob')->nullable();
            $table->string('bvn')->nullable();
            $table->text('about')->nullable();
            $table->text('address')->nullable();
            $table->float('balance')->default(0);
            $table->string('password');
            $table->json('other_details')->nullable();
            $table->string('photo')->default('placeholder.png');
            $table->bigInteger('level_id')->unsigned();
            $table->boolean('verified')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('level_id')->references('id')->on('agent_levels')->onDelete('cascade')->onUpdate('no action');
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
