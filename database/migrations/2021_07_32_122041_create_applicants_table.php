<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicants', function (Blueprint $table) {
            $table->unsignedBigInteger("jobs_id");
            $table->unsignedBigInteger("creators_id");
            $table->text("text");
        });

        Schema::table('applicants', function($table) {
            $table->foreign('jobs_id')->references('id')->on('jobs')->onDelete('cascade');
            $table->foreign('creators_id')->references('id')->on('creators')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->dropForeign(['jobs_id']);
            $table->dropForeign(['creators_id']);
        });
        Schema::dropIfExists('applicants');
    }
}
