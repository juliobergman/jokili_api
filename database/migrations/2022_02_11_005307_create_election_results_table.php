<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElectionResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('election_results', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->nullable()->foreign('user_id')->references('id')->on('users');
            $table->foreignId('position_id')->nullable()->foreign('position_id')->references('id')->on('positions');
            $table->foreignId('nominee_id')->nullable()->foreign('nominee_id')->references('user_id')->on('nominees');
            $table->foreignId('election_id')->nullable()->foreign('election_id')->references('id')->on('elections');

            // $table->integer('user_id');
            // $table->integer('position_id');
            // $table->integer('nominee_id');
            // $table->integer('election_id');

            // Unique
            // $table->unique(['user_id','position_id','nominee_id','election_id'], 'unicus');
            
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
        Schema::dropIfExists('election_results');
    }
}
