<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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
            'Fashion',
        ];

        $all_sub_categories = [
            'Vehicles' => [
                'Cars',
                'Buses',
                'Motorcycles & Scooters',
                'Trucks & Trailers',
                'Vehicle Accessories',
                'Watercraft & Boats',
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
                'Tablets',
            ],

            'Electronics & Appliances' => [
                'Laptops & Computers',
                'TV & DVD Equipments',
                'Audio & Music Equipments',
                'Computer Accessories',
                'Computer Hardware',
                'Printers & Scanners',
                'Headphones',
            ],

            'Home & Furniture' => [
                'Home Appliances',
                'Kitchen Appliances',
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
            ],

            'Sports, Arts & Outdoor' => [
                'Sport Equipments',
                'Arts & Crafts',
                'Books',
                'Games',
                'Musical Instruments',
                'Camping Gear',
            ],

            'Agriculture & Food' => [
                'Feeds & Supplements',
                'Livestocks & Poultry',
                'Farm Machinery & Equipments',
                'Drinks & Meals',
            ],

            'Services' => [
                'Automotive Services',
                'Building & Trade Services',
                'Cleaning Services',
                'Computer & IT Services',
                'Entertainment Services',
                'Catering & Event Services',
                'Health & Beauty Services',
            ],

            'Repair & Construction' => [
                'Building Materials',
                'Doors',
                'Electrical Equipment',
                'Plumbing & Water Supply',
                'Solar Energy',
            ],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => strtolower($category),
                'slug' => Str::slug($category, '-'),
            ]);
        }

        foreach ($all_sub_categories as $category => $sub_categories) {
            $category_id = Category::where('name', strtolower($category))->value('id');

            if ($category_id) {
                foreach ($sub_categories as $sub_category) {
                    SubCategory::create([
                        'category_id' => $category_id,
                        'name' => $sub_category,
                        'slug' => Str::slug($sub_category, '-'),
                    ]);
                }
            }
        }
    }
}
