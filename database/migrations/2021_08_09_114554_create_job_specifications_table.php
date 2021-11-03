<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobSpecificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_specifications', function (Blueprint $table) {
            $table->unsignedBigInteger("jobs_id");
            $table->unsignedBigInteger("specifications_id");
        });


        Schema::table('job_specifications', function($table) {
            $table->foreign('jobs_id')->references('id')->on('jobs')->onDelete('cascade');
            $table->foreign('specifications_id')->references('id')->on('specifications')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('job_specifications', function (Blueprint $table) {
            $table->dropForeign(['jobs_id']);
            $table->dropForeign(['specifications_id']);
        });
        Schema::dropIfExists('job_specifications');
    }
}
