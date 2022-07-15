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
        Schema::create('emails', function (Blueprint $table) {
            $table->id();
            $table->string('email_from')->nullable();;
            $table->string('email_to')->nullable();;
            $table->string('email_type')->nullable();;
            $table->string('email_message')->nullable();;
            $table->string('email_subject')->nullable();;
            $table->string('email_sender_ip_address')->nullable();;
            $table->string('email_request_type')->nullable();;
            $table->dateTime('email_created_at', $precision = 0)->nullable();;
            $table->dateTime('email_sent_at', $precision = 0)->nullable();;
            $table->boolean('email_is_succesfully_sent')->default(false);
            $table->string('email_sender_driver')->nullable();;
            $table->string('final_email_sender')->nullable();;
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
        Schema::dropIfExists('emails');
    }
};
