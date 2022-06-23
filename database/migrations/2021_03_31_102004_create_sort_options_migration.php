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
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); //['vehicle', 'phone', 'computer'];
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('conditions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type')->default('general'); //['vehicle', 'property', 'general', 'electronics];
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('furnishings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('prices', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type')->default('general'); //['general', 'vehicle', 'property'];
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('second_conditions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('bodies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('fuels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('transmissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('colours', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type')->default('property');
            // ['property', 'computer', 'equipment', 'furniture', 'body', 'hair';
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('genders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('rams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('storage_capacities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('storage_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('processors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('operating_systems', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('screen_sizes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('main_materials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('main_stones', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('age_levels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('job_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('expected_salaries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('bathrooms', function (Blueprint $table) {
            $table->id();
            $table->string('value');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('bedrooms', function (Blueprint $table) {
            $table->id();
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
        Schema::dropIfExists('genders');
        Schema::dropIfExists('brands');
        Schema::dropIfExists('conditions');
        Schema::dropIfExists('second_conditions');
        Schema::dropIfExists('bodies');
        Schema::dropIfExists('fuels');
        Schema::dropIfExists('transmissions');
        Schema::dropIfExists('colours');
        Schema::dropIfExists('furnishings');
        Schema::dropIfExists('prices');
        Schema::dropIfExists('rams');
        Schema::dropIfExists('types');
        Schema::dropIfExists('facilities');
        Schema::dropIfExists('storage_capacities');
        Schema::dropIfExists('storage_types');
        Schema::dropIfExists('processors');
        Schema::dropIfExists('operating_systems');
        Schema::dropIfExists('screen_sizes');
        Schema::dropIfExists('materials');
        Schema::dropIfExists('main_materials');
        Schema::dropIfExists('main_stones');
        Schema::dropIfExists('age_levels');
        Schema::dropIfExists('job_types');
        Schema::dropIfExists('expected_salaries');
        Schema::dropIfExists('bathrooms');
        Schema::dropIfExists('bedrooms');
    }
};
