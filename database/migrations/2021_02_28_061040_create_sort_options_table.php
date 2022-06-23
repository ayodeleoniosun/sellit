<?php

use App\Models\SortOption;
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
        Schema::create('sort_options', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        $sort_options = [
            'age_level', 'body', 'brand', 'colour', 'condition', 'expected_salary', 'facility', 'fuel', 'furnishing',
            'gender', 'job_type', 'material', 'operating_system', 'price', 'processor', 'ram', 'screen_size',
            'second_condition', 'storage_capacity', 'storage_type', 'transmission', 'type', 'bathroom', 'bedroom'
        ];

        foreach ($sort_options as $option) {
            SortOption::create(['name' => $option]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sort_options');
    }
};
