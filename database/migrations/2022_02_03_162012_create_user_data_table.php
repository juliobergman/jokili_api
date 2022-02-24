<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // Contact Data
            $table->string('phone')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->text('address')->nullable();
            // Personal Data
            $table->string('citizenship')->nullable();
            $table->enum('id_prefix', ['V-','E-','P-'])->nullable();
            $table->string('id_number')->nullable();
            $table->string('occupation')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->date('birth_at')->nullable();
            $table->string('birthplace')->nullable();
            // Jokili Data
            $table->bigInteger('number')->nullable();
            $table->unsignedBigInteger('rank')->default(13);
            $table->foreign('rank')->references('id')->on('ranks');
            $table->date('member_since')->nullable();
            $table->boolean('mask')->default(false);
            $table->unsignedBigInteger('godfather')->nullable();
            $table->foreign('godfather')->references('id')->on('users');
            $table->date('zunftrat_in')->nullable();
            $table->date('zunftrat_out')->nullable();
            $table->unsignedBigInteger('status')->default(1);
            // System
            $table->string('avatar')->default('/storage/factory/avatar/misc/avatar-user.jpg');
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
        Schema::dropIfExists('user_data');
    }
}
