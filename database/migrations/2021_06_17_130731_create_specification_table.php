<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpecificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('specifications', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned()->index();
            $table->string('name', 255);
            $table->string('icon', 255);
            $table->decimal('cost', 8,2);
            $table->unsignedBigInteger('subcategories_fk');
            $table->smallInteger("contact")->default(0);
        });

        Schema::table('specifications', function($table) {
            $table->foreign('subcategories_fk')->references('id')->on('subcategories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('specifications', function (Blueprint $table) {
            $table->dropForeign(['subcategories_fk']);
        });
        Schema::dropIfExists('specifications');
    }
}
