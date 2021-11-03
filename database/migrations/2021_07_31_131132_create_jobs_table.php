<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('clients_fk');
            $table->string('name', 255);
            $table->text('description');
            $table->smallInteger('location')->default(0);
            $table->string('street', 255)->nullable();
            $table->integer('plz')->nullable();
            $table->unsignedBigInteger('categories_fk');
            $table->unsignedBigInteger('subcategories_fk');
            $table->smallInteger('specifications')->default(0);
            $table->smallInteger('skills')->default(0);
            $table->unsignedBigInteger('complexities_fk');
            $table->decimal('cost', 8,2);
            $table->unsignedBigInteger('payment_types_fk');
            $table->unsignedBigInteger('status_fk')->default(1);
            $table->date('eventdate')->nullable();
            $table->date('end');
            $table->unsignedBigInteger('creators_fk')->nullable();
            $table->smallInteger('attachments')->default(0);
            $table->timestamps();
        });

        Schema::table('jobs', function($table) {
            $table->foreign('clients_fk')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('categories_fk')->references('id')->on('categories');
            $table->foreign('subcategories_fk')->references('id')->on('subcategories');
            $table->foreign('complexities_fk')->references('id')->on('complexities');
            $table->foreign('payment_types_fk')->references('id')->on('payment_types');
            $table->foreign('status_fk')->references('id')->on('status');
            $table->foreign('creators_fk')->references('id')->on('creators');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropForeign(['clients_fk']);
            $table->dropForeign(['categories_fk']);
            $table->dropForeign(['subcategories_fk']);
            $table->dropForeign(['complexities_fk']);
            $table->dropForeign(['payment_types_fk']);
            $table->dropForeign(['status_fk']);
            $table->dropForeign(['creators_fk']);
        });
        Schema::dropIfExists('jobs');
    }
}
