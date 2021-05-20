<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Modules\Api\V1\Models\Category;
use App\Modules\Api\V1\Models\SubCategory;
use Illuminate\Support\Str;

class AddSlugColumnToCategoriesAndSubCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('category', function (Blueprint $table) {
            $table->string('slug')->after('name');
        });

        Schema::table('sub_category', function (Blueprint $table) {
            $table->string('slug')->after('name');
        });

        $categories = Category::all();

        foreach ($categories as $category) {
            $category = Category::find($category->id);
            $category->slug = Str::slug($category->name, "_");
            $category->save();
        }

        $sub_categories = SubCategory::all();

        foreach ($sub_categories as $sub_category) {
            $sub_category = SubCategory::find($sub_category->id);
            $sub_category->slug = Str::slug($sub_category->name, "_");
            $sub_category->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('slugs_for_categories');
    }
}
