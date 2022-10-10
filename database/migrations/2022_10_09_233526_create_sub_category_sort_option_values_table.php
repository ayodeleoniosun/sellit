<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_category_sort_option_values', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sub_cat_sort_option_id');
            $table->foreign('sub_cat_sort_option_id')
                ->references('id')
                ->on('sub_category_sort_options');
            $table->integer('value');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_category_sort_option_values');
    }
};
