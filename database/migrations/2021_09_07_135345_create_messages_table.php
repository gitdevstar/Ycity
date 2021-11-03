<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('jobs_fk');
            $table->unsignedBigInteger('users_fk');
            $table->text('message')->nullable();
            $table->string('file')->nullable();
            $table->string('type');
            $table->timestamps();
        });

        Schema::table('messages', function($table) {
            $table->foreign('jobs_fk')->references('id')->on('jobs')->onDelete('cascade');
            $table->foreign('users_fk')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['jobs_fk']);
            $table->dropForeign(['users_fk']);
        });
        Schema::dropIfExists('messages');
    }
}
