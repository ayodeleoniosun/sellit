<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Modules\Api\V1\Models\Category;
use App\Modules\Api\V1\Models\SubCategory;

class CreateCategoryMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('icon')->nullable()->default('default.jpg');
            $table->timestamps();
            $table->unsignedInteger('active_status')->default(1);

            $table->foreign('active_status')->references('id')->on('active_status');
        });

        Schema::create('sub_category', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->string('name');
            $table->string('metadata')->nullable();
            $table->timestamps();
            $table->unsignedInteger('active_status')->default(1);

            $table->foreign('category_id')->references('id')->on('category');
            $table->foreign('active_status')->references('id')->on('active_status');
        });

        $categories = [
            'Vehicles',
            'Property',
            'Mobile Phones & Tablets',
            'Electronics & Appliances',
            'Home & Furniture',
            'Health & Beauty',
            'Sports, Arts & Outdoor',
            'Agriculture & Food',
            'Services',
            'Repair & Construction',
            'Fashion'
        ];

        $all_sub_categories = [
            'Vehicles' => [
                'Cars',
                'Buses',
                'Motorcycles & Scooters',
                'Trucks & Trailers',
                'Vehicle Accessories',
                'Watercrafts & Boats'
            ],

            'Property' => [
                'Houses & Apartments for Rent',
                'Houses & Apartments for Sale',
                'Lands & Plots for Rent',
                'Lands & Plots for Sale',
                'Commercial Property for Rent',
                'Commercial Property for Sale',
                'Event Centres, Venues & Workstations',
                'Short Let',
            ],

            'Mobile phones & tablets' => [
                'Mobile Phones',
                'Accessories for Mobile Phones & Tablets',
                'Smart Watches & Trackers',
                'Tablets'
            ],

            'Electronics & Appliances' => [
                'Laptops & Computers',
                'TV & DVD Equipments',
                'Audio & Music Equipments',
                'Computer Accessories',
                'Compuer Hardware',
                'Printers & Scanners',
                'Headphones'
            ],

            'Home & Furniture' => [
                'Home Appliances',
                'Kitchen Appliances'
            ],

            'Health & Beauty' => [
                'Fragrance',
                'Hair Beauty',
                'Makeup',
                'Skin Care',
            ],

            'Fashion' => [
                'Bags',
                'Clothing',
                'Jewelry',
                'Shoes',
                'Watches',
                'Wedding wear & Accessories',
                ''
            ],
            
            'Sports, Arts & Outdoor' => [
                'Sport Equipments',
                'Arts & Crafts',
                'Books',
                'Games',
                'Musical Instruments',
                'Camping Gear'
            ],

            'Agriculture & Food' => [
                'Feeds & Supplements',
                'Livestocks & Poultry',
                'Farm Machinery & Equipments',
                'Drinks & Meals'
            ],

            'Services' => [
                'Automotive Services',
                'Building & Trade Services',
                'Cleaning Services',
                'Computer & IT Services',
                'Entertainment Services',
                'Catering & Event Services',
                'Health & Beauty Services'
            ],

            'Repair & Construction' => [
                'Building Materials',
                'Doors',
                'Electrical Equipment',
                'Plumbing & Water Supply',
                'Solar Enery'
            ]
        ];

        foreach ($categories as $category) {
            Category::create(['name' => strtolower($category)]);
        }

        foreach ($all_sub_categories as $category => $sub_categories) {
            $category_id = Category::where('name', $category)->value('id');
            
            if ($category_id) {
                foreach ($sub_categories as $sub_category) {
                    SubCategory::create([
                        'category_id' => $category_id,
                        'name' => $sub_category
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category');
        Schema::dropIfExists('sub_category');
    }
}
