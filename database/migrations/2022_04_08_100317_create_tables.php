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
        Schema::create('countries', function (Blueprint $table) {
            $table->id('cnt_id');
            $table->string('cnt_code',3)->nullable();
            $table->string('cnt_title',100)->nullable();
            $table->dateTime('cnt_created', $precision = 0);
        });

        Schema::create('numbers', function (Blueprint $table) {
            $table->id('num_id');
            $table->foreignId('cnt_id')->references('cnt_id')->on('countries')->onDelete('cascade');
            $table->string('num_number',20)->nullable();
            $table->dateTime('num_created', $precision = 0);
        });

        Schema::create('send_log', function (Blueprint $table) {
            $table->id('log_id');
            $table->foreignId('usr_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('num_id')->references('num_id')->on('numbers')->onDelete('cascade');
            $table->string('log_messsage')->nullable();
            $table->boolean('log_success');
            $table->date('log_created');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {   Schema::dropIfExists('send_log');
        Schema::dropIfExists('numbers');
        Schema::dropIfExists('countries');
        
    }
};
