<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('sub_category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('location_id')->nullable()->constrained('cities')->nullOnDelete();
            $table->foreignId('seller_id')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->longText('description');
            $table->string('price');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('ads_pictures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ads_id')->constrained()->cascadeOnDelete();
            $table->foreignId('file_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('ads_sort_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ads_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sort_option_id')->nullable()->constrained()->nullOnDelete();
            $table->string('value');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ads_pictures');
        Schema::dropIfExists('ads_sort_options');
        Schema::dropIfExists('ads');
    }
};
