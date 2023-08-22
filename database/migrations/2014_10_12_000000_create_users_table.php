<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
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
            $table->string('email')->nullable()->unique();
            $table->string('phoneNumber')->nullable()->unique();
            $table->string('registerBy')->nullable(); // The device through which the user registered.
            $table->string('password');
            $table->unsignedTinyInteger('isActive')->default(0); //  0 = inactive  ,1 = active
            $table->string('role')->default('client');
            $table->longText('remember_token')->nullable();
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
        Schema::dropIfExists('users');
    }
};
