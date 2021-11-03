<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('creators', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('users_fk');
            $table->date('birthdate');
            $table->text('description');
            $table->string('street', 255);
            $table->integer('plz');
            $table->integer('telephone');
            $table->boolean('activated')->default(0);
            $table->timestamps();
        });

        Schema::table('creators', function($table) {
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
        Schema::table('creators', function (Blueprint $table) {
            $table->dropForeign(['users_fk']);
        });
        Schema::dropIfExists('creators');
    }
}
