<?php

namespace Database\Seeders;

use App\Models\SortOption;
use App\Models\SortOptionValues;
use Illuminate\Database\Seeder;

class SortOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //age levels

        $ageLevels = [
            'Birth - 24 months',
            '2 - 4 years',
            '5 - 7 years',
            '8 - 13 years',
            'Above 14 years'
        ];

        $this->seedSortOptionValues($ageLevels, 'age-levels');

        //bodies

        $bodies = [
            'Van', 'Station Wagon', 'Sedan', 'SUV', 'Pickup', 'Panel Van', 'Minivan', 'Hatchback', 'Crossover', 'Coupe',
            'Convertible', 'Convertible Coupe'
        ];

        $this->seedSortOptionValues($bodies, 'bodies');

        //brands

        $allBrands = [
            'vehicle' => [
                'Toyota', 'Lexus', 'Mercedez-Benz', 'Honda', 'Ford', 'Acura', 'Audi', 'BMW', 'Bentley', 'Cadillac',
                'Chery', 'Chevrolet', 'Chrysler', 'Citroen', 'Daihatsu', 'Dodge', 'Ferrari', 'Flat', 'GAC', 'GMC',
                'Great Wall', 'Hummer', 'Hyundai', 'Infiniti', 'Isuzu', 'Iveco', 'IVM', 'IAC', 'Jaquar', 'Jeep', 'Kia',
                'Lamborghini', 'Land Rover', 'Lincoln', 'Maserati', 'Mazda', 'Mercury', 'MG', 'Mini', 'Mitsubishi',
                'Nissan', 'Nord', 'Opel', 'Peugeot', 'Polaris', 'Pontiac', 'Porsche', 'Renault', 'Rolls-Royce', 'Rover',
                'Saab', 'Samsung', 'Saturn', 'Scion', 'Skoda', 'SMA', 'Smart', 'SsangYong', 'Subaru', 'Suzuki', 'Tata',
                'TVS', 'Volkswagen', 'Volvo', 'Others'
            ],

            'phone' => [
                'Apple', 'Samsung', 'Tecno', 'Infinix', 'Huawei', 'Afrione', 'Alcatel', 'Amazon', 'Amoi', 'Archos',
                'Asus', 'AT & T', 'Blackberry', 'Blackview', 'BLU', 'Bontel', 'Cat', 'Coolpad', 'Cubot', 'Doogee',
                'Elephone', 'Fero', 'Freetel', 'Gionee', 'Google', 'Hisense', 'HP', 'Homtom', 'Hotwav', 'HTC', 'Imose',
                'Infocus', 'Injoo', 'iPRO', 'Iridium', 'Itel', 'Ivvi', 'Kgtel', 'K-Mous', 'K-Touch', 'Kenxinda', 'Lava',
                'Lenovo', 'LeEco', 'Leago', 'LG', 'M-Horze', 'Meizu', 'Micromax', 'Mione', 'Modu', 'Motorola',
                'Microsoft', 'Mi-Tribe', 'MTN', 'Nokia', 'Nomu', 'Olla', 'OnePlus', 'Oppo', 'Oukitel', 'Pano',
                'Panasonic', 'Pantech', 'Partnermobile', 'Qmobile', 'Ravoz', 'Razor', 'Realme', 'Santin', 'Sharp',
                'Siemens', 'Smartisan', 'Sugar', 'Sony', 'Sony Ericsson', 'Sicco', 'Sowhat', 'Snokor', 'Rocket', 'SQ',
                'Swipe Technologies', 'Turaya', 'T-mobile', 'Tesla', 'Ulefone', 'Umidigi', 'Vertu', 'Vivo', 'Vernee',
                'Vodafone', 'Wiko', 'Wintouch', 'Xiaomi', 'X-Tigi', 'Yezz', 'ZTE', 'Zopo', 'Others'
            ],

            'computer' => [
                'HP', 'Dell', 'Apple', 'Lenovo', 'Acer', 'Advent', 'Asus', 'Chuwi', 'Fujitsu', 'Gigabyte', 'Hasee',
                'i-Life', 'MSI', 'Novatech', 'Razer', 'Samsung', 'Touchmate', 'Zinox', 'Huawei', 'Airis', 'Alienware',
                'Avantis', 'Avell', 'Brian', 'Clevo', 'Compaq', 'Cyberpower', 'EduPad', 'Evoo', 'Gateway', 'Geo',
                'Google', 'Haier', 'Howard', 'Injoo', 'Linx', 'LG', 'Medion', 'Microsoft', 'Minisonic', 'NEC', 'Omatek',
                'One-Netbook', 'Origin PC', 'Packard Bell', 'Panasonic', 'Prime', 'RM Minibook', 'Sager', 'Sony',
                'Stone', 'Toshiba', 'Turbo-X', 'Venom', 'Vinovo', 'Vision Computer', 'Xiaomi', 'Others'
            ]
        ];

        $this->seedMultipleSortOptionValues($allBrands, 'brands');

        //colours

        $colours = [
            'Black', 'Silver', 'Gray', 'White', 'Blue', 'Belge', 'Brown', 'Gold', 'Green', 'Orange', 'Pearl', 'Pink',
            'Purple', 'Red', 'Yellow', 'Others'
        ];

        $this->seedSortOptionValues($colours, 'colours');

        //conditions

        $allConditions = [
            'general'     => ['Brand New', 'Used'],
            'vehicle'     => ['Nigeria Used', 'Foreign Used'],
            'property'    => ['Renovated', 'Old'],
            'electronics' => ['Refurbished']
        ];

        $this->seedMultipleSortOptionValues($allConditions, 'conditions');

        //expected salaries

        $salaries = [
            'Below 50k',
            '50k - 100k',
            '100k - 200k',
            '200k - 500k',
            'Above 500k'
        ];

        $this->seedSortOptionValues($salaries, 'expected-salaries');

        //facilities

        $facilities = [
            'Kitchen Cabinets', 'Tiled Floor', 'Balcony', 'Kitchen Shelf', 'Hot Water', '24 hours electricity',
            'Chandelier', 'Dining Area', 'Dish Washer', 'Pop Ceiling', 'Prepaid Meter', 'Refrigerator', 'TV',
            'Wardrobe', 'Wi-Fi'
        ];

        $this->seedSortOptionValues($facilities, 'facilities');

        //fuels
        $fuels = [
            'Petrol / Electric Hybrid', 'Petrol', 'Natural Gas', 'Gasoline', 'Flex-Fuel', 'Electric',
            'Diesel / Electric Hybrid', 'Diesel'
        ];

        $this->seedSortOptionValues($fuels, 'fuels');

        //furnishings

        $furnishings = [
            'Furnished', 'Semi-Furnished', 'Unfurnished'
        ];

        $this->seedSortOptionValues($furnishings, 'furnishings');

        //genders

        $genders = [
            'Men', 'Women', 'Unisex'
        ];

        $this->seedSortOptionValues($genders, 'genders');

        //job types

        $jobTypes = [
            'Full-time', 'Part-time', 'Temporary', 'Contract', 'Internship'
        ];

        $this->seedSortOptionValues($jobTypes, 'job-types');

        //materials

        $materials = [
            'Aluminium', 'Ceramic', 'Copper', 'Faux Leather', 'Genuine Leather', 'Nylon', 'Rubber', 'Stainless Steel'
        ];

        $this->seedSortOptionValues($materials, 'materials');

        //operating systems

        $operatingSystems = [
            'DOS', 'Free DOS', 'Windows XP', 'Windows 7', 'Windows 8', 'Windows 10', 'Linux', 'Ubuntu', 'MacOS'
        ];

        $this->seedSortOptionValues($operatingSystems, 'operating-systems');

        // all prices

        $allPrices = [
            'vehicle'  => ['Below 1M', '1 - 5M', '5 - 10M', '10 - 15M', 'Above 15M'],
            'property' => ['Below 500k', '500k - 1M', '1M - 3M', '3M - 6M', '6M - 10M', 'Above 10M']
        ];

        $this->seedMultipleSortOptionValues($allPrices, 'prices');

        //processors

        $processors = [
            'Intel', 'Intel Core i3', 'Intel Core i5', 'Intel Core i7', 'Intel Core i9', 'Intel Core 2 Duo',
            'Intel Core M', 'Intel Core 2 Quad', 'Intel Celeron', 'Intel Pentium', 'Intel Atom', 'Intel Xeon',
            'Intel AMD', 'AMD Ryzen', 'AMD A4', 'AMD A6', 'AMD A8', 'AMD A10', 'Nvidia'
        ];

        $this->seedSortOptionValues($processors, 'processors');

        //RAMS

        $rams = [
            'Below 512MB', '1 - 4GB', '6GB', '8GB', '12GB'
        ];

        $this->seedSortOptionValues($rams, 'rams');

        //screen sizes

        $screenSizes = [
            'Below 4 inches', '4 - 8 inches', '8 - 15 inches', 'Above 15 inches'
        ];

        $this->seedSortOptionValues($screenSizes, 'screen-sizes');

        //second conditions

        $secondConditions = [
            'No faults', 'After Crash', 'Engine Issue', 'First Owner', 'First Registration', 'Gear Issue',
            'Need Body Repair', 'Need Repainting', 'Unpainted', 'Original Parts', 'Wiring problems', 'Need Repair'
        ];

        $this->seedSortOptionValues($secondConditions, 'second-conditions');

        //storage capacities

        $storageCapacities = [
            'Below 512MB', '1 - 8GB', '8 - 64GB', '64 - 512GB', 'Above 512GB'
        ];

        $this->seedSortOptionValues($storageCapacities, 'storage-capacities');

        //storage types

        $storageTypes = [
            'HDD', 'SSD', 'SSHD (Hybrid)'
        ];

        $this->seedSortOptionValues($storageTypes, 'storage-types');

        //transmissions

        $transmissions = [
            'Automatic', 'Manual'
        ];

        $this->seedSortOptionValues($transmissions, 'transmissions');

        //all types

        $allTypes = [
            'property' => ['Duplex', 'House', 'Maisonette', 'Mansion', 'Shared Apartment', 'Townhouse', 'Villa', 'Flat',
                'Bungalow', 'Chalet', 'Condo', 'MiniFlat', 'Farmhouse', 'Penthouse', 'Room & Parlour',
                'Studio Apartment'
            ],

            'computer' => ['Desktop', 'Laptop'],

            'equipment' => ['Speakers', 'Home Theatres', 'Sound Systems', 'Microphones', 'Amplifiers', 'CD Players'],

            'furniture' => ['Tables', 'Chairs', 'Sofas', 'TV Stands', 'Beds', 'Armchairs', 'Bag Racks', 'Bar Stools',
                'Benches', 'Bed Frames', 'Bookcases', 'Shelfs', 'Cubicles', 'Cabinets', 'Drawers', 'Dressers',
                'Electric Fireplaces', 'Headboards', 'Hangers', 'Lockers', 'Mattressess', 'Mirrors'
            ],

            'body' => ['Shower Gels', 'Body Oils', 'Toothpaste', 'Bath Sponges', 'Antiseptics', 'Body Scrubs',
                'Body Lotions', 'Dental Floss'
            ]
        ];

        $this->seedMultipleSortOptionValues($allTypes, 'type');
    }

    private function seedMultipleSortOptionValues(array $allValues, string $sortOption) {
        $sortOptionId = SortOption::whereSlug($sortOption)->value('id');

        foreach ($allValues as $type => $values) {
            foreach ($values as $value) {
                $valueExists = SortOptionValues::where([
                    'sort_option_id' => $sortOptionId,
                    'type' => $type,
                    'value' => $value
                ])->exists();

                if (!$valueExists) {
                    SortOptionValues::create([
                        'sort_option_id' => $sortOptionId,
                        'type' => $type,
                        'value' => $value
                    ]);
                }
            }
        }
    }

    private function seedSortOptionValues(array $values, string $sortOption) {
        $sortOptionId = SortOption::whereSlug($sortOption)->value('id');

        foreach ($values as $value) {
            $valueExists = SortOptionValues::where([
                'sort_option_id' => $sortOptionId,
                'value' => $value
            ])->exists();

            if (!$valueExists) {
                SortOptionValues::create([
                    'sort_option_id' => $sortOptionId,
                    'value' => $value
                ]);
            }
        }
    }
}
