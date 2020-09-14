<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('gender')->nullable();
            $table->string('address')->nullable();
            $table->float('balance')->default(0);
            $table->string('photo')->default('placeholder.png');
            $table->string('password');
            $table->string('device_unique');
            $table->json('other_details')->nullable();
            $table->foreignId('area_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('no action');
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
        Schema::dropIfExists('users');
    }
}
