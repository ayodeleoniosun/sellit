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
            $table->string('slug');
            $table->timestamps();
        });

        $sortOptions = [
            'age levels', 'bathrooms', 'bedrooms', 'bodies', 'brands', 'colours', 'conditions', 'expected salaries', 'facilities', 'fuels', 'furnishings',
            'genders', 'job types', 'materials', 'operating systems', 'prices', 'processors', 'rams', 'screen sizes',
            'second conditions', 'storage capacities', 'storage types', 'transmissions', 'type'
        ];

        foreach ($sortOptions as $option) {
            SortOption::create([
                'name' => $option,
                'slug' => \Illuminate\Support\Str::kebab($option)
            ]);
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
