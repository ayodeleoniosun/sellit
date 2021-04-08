<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubCategorySortOptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_category_sort_option', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('sub_category_id');
            $table->unsignedInteger('sort_option_id');
            $table->timestamps();
            $table->unsignedInteger('active_status')->default(1);

            $table->foreign('sub_category_id')->references('id')->on('sub_category');
            $table->foreign('sort_option_id')->references('id')->on('sort_option');
            $table->foreign('active_status')->references('id')->on('active_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_category_sort_option');
    }
}
