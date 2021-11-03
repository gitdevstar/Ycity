<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobSkillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_skills', function (Blueprint $table) {
            $table->unsignedBigInteger("jobs_id");
            $table->unsignedBigInteger('skills_id');
        });


        Schema::table('job_skills', function($table) {
            $table->foreign('jobs_id')->references('id')->on('jobs')->onDelete('cascade');
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
        Schema::table('job_skills', function (Blueprint $table) {
            $table->dropForeign(['jobs_id']);
            $table->dropForeign(['skills_id']);
        });
        Schema::dropIfExists('job_skills');
    }
}
