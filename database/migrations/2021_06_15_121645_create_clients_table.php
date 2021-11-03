<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('users_fk');
            $table->string('name', 255);
            $table->text('description');
            $table->string('street', 255);
            $table->integer('plz');
            $table->string('email')->unique();
            $table->string('website', 255);
            $table->integer('telephone');
            $table->timestamps();

        });

        Schema::table('clients', function($table) {
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
        Schema::table('clients', function (Blueprint $table) {
            $table->dropForeign(['users_fk']);
        });
        Schema::dropIfExists('clients');
    }
}
