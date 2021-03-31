<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('review', function (Blueprint $table) {
            $table->increments('id');
            $table->string('rating', 100);
            $table->longText('comment');
            $table->unsignedInteger('buyer_id');
            $table->unsignedInteger('seller_id');
            $table->unsignedInteger('ads_id');
            $table->timestamps();
            $table->unsignedInteger('active_status')->default(1);

            $table->foreign('buyer_id')->references('id')->on('user');
            $table->foreign('seller_id')->references('id')->on('user');
            $table->foreign('ads_id')->references('id')->on('ads');
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
        Schema::dropIfExists('review');
    }
}
