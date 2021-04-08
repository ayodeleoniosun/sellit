<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdsMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ads', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('sub_category_id');
            $table->unsignedInteger('seller_id');
            $table->string('name');
            $table->longText('description');
            $table->string('price');
            $table->timestamps();
            $table->unsignedInteger('active_status')->default(1);

            $table->foreign('category_id')->references('id')->on('category');
            $table->foreign('sub_category_id')->references('id')->on('sub_category');
            $table->foreign('seller_id')->references('id')->on('user');
            $table->foreign('active_status')->references('id')->on('active_status');
        });

        Schema::create('ads_picture', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ads_id');
            $table->unsignedInteger('file_id');
            $table->string('random_id');
            $table->timestamps();
            $table->unsignedInteger('active_status')->default(1);

            $table->foreign('ads_id')->references('id')->on('ads');
            $table->foreign('file_id')->references('id')->on('file');
            $table->foreign('active_status')->references('id')->on('active_status');
        });

        Schema::create('ads_sort_option', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ads_id');
            $table->unsignedInteger('sort_option_id');
            $table->string('value');
            $table->timestamps();
            $table->unsignedInteger('active_status')->default(1);

            $table->foreign('ads_id')->references('id')->on('ads');
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
        Schema::dropIfExists('ads');
        Schema::dropIfExists('ads_picture');
    }
}
