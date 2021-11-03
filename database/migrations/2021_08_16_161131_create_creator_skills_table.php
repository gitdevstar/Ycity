<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreatorSkillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('creator_skills', function (Blueprint $table) {
            $table->unsignedBigInteger("creators_id");
            $table->unsignedBigInteger("skills_id");
        });

        Schema::table('creator_skills', function($table) {
            $table->foreign('creators_id')->references('id')->on('creators')->onDelete('cascade');
            $table->foreign('skills_id')->references('id')->on('skills')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('creator_skills', function (Blueprint $table) {
            $table->dropForeign(['creators_id']);
            $table->dropForeign(['skills_id']);
        });
        Schema::dropIfExists('creator_skills');
    }
}
