<?php

use App\Modules\Api\V1\Models\SortOption;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSortOptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sort_option', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
            $table->unsignedInteger('active_status')->default(1);

            $table->foreign('active_status')->references('id')->on('active_status');
        });

        $sort_options = [
            'age_level', 'body', 'brand', 'colour', 'condition', 'expected_salary', 'facility', 'fuel', 'furnishing', 'gender', 'job_type', 'material', 'operating_system', 'price', 'processor', 'ram', 'screen_size', 'second_condition', 'storage_capacity', 'storage_type', 'transmission', 'type', 'bathroom', 'bedroom'
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
        Schema::dropIfExists('sort_option');
    }
}
