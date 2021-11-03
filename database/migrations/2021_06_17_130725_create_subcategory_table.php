<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubcategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subcategories', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned()->index();
            $table->string('name', 255);
            $table->string('icon', 255);
            $table->decimal('cost', 8,2);
            $table->unsignedBigInteger('categories_fk');
        });

        Schema::table('subcategories', function($table) {
            $table->foreign('categories_fk')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subcategories', function (Blueprint $table) {
            $table->dropForeign(['categories_fk']);
        });
        Schema::dropIfExists('subcategories');
    }
}
