<?php

use App\Modules\Api\V1\Models\ActiveStatus;
use App\Modules\Api\V1\Models\AgeLevel;
use App\Modules\Api\V1\Models\Body;
use Illuminate\Database\Migrations\Migration;
use App\Modules\Api\V1\Models\Brand;
use App\Modules\Api\V1\Models\Colour;
use App\Modules\Api\V1\Models\Condition;
use App\Modules\Api\V1\Models\ExpectedSalary;
use App\Modules\Api\V1\Models\Facility;
use App\Modules\Api\V1\Models\Fuel;
use App\Modules\Api\V1\Models\Furnishing;
use App\Modules\Api\V1\Models\Gender;
use App\Modules\Api\V1\Models\JobType;
use App\Modules\Api\V1\Models\Material;
use App\Modules\Api\V1\Models\OperatingSystem;
use App\Modules\Api\V1\Models\Price;
use App\Modules\Api\V1\Models\Processor;
use App\Modules\Api\V1\Models\Type;
use App\Modules\Api\V1\Models\Ram;
use App\Modules\Api\V1\Models\ScreenSize;
use App\Modules\Api\V1\Models\SecondCondition;
use App\Modules\Api\V1\Models\StorageCapacity;
use App\Modules\Api\V1\Models\StorageType;
use App\Modules\Api\V1\Models\Transmission;

class PreloadSortOptionsMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $all_brands = [
            'vehicle' => [
                'Toyota', 'Lexus', 'Mercedez-Benz', 'Honda', 'Ford', 'Acura', 'Audi', 'BMW', 'Bentley', 'Cadillac', 'Chery', 'Chevrolet', 'Chrysler', 'Citroen', 'Daihatsu', 'Dodge', 'Ferrari', 'Flat', 'GAC', 'GMC', 'Great Wall', 'Hummer', 'Hyundai', 'Infiniti', 'Isuzu', 'Iveco', 'IVM', 'IAC', 'Jaquar', 'Jeep', 'Kia', 'Lamborghini', 'Land Rover', 'Lincoln', 'Maserati', 'Mazda', 'Mercury', 'MG', 'Mini', 'Mitsubishi', 'Nissan', 'Nord', 'Opel', 'Peugeot', 'Polaris', 'Pontiac', 'Porsche', 'Renault', 'Rolls-Royce', 'Rover', 'Saab', 'Samsung', 'Saturn', 'Scion', 'Skoda', 'SMA', 'Smart', 'SsangYong', 'Subaru', 'Suzuki', 'Tata', 'TVS', 'Volkswagen', 'Volvo', 'Others'
            ],

            'phone' => [
                'Apple', 'Samsung', 'Tecno', 'Infinix', 'Huawei', 'Afrione', 'Alcatel', 'Amazon', 'Amoi', 'Archos', 'Asus', 'AT & T', 'Blackberry', 'Blackview', 'BLU', 'Bontel', 'Cat', 'Coolpad', 'Cubot', 'Doogee', 'Elephone', 'Fero', 'Freetel', 'Gionee', 'Google', 'Hisense', 'HP', 'Homtom', 'Hotwav', 'HTC', 'Imose', 'Infocus', 'Injoo', 'iPRO', 'Iridium', 'Itel', 'Ivvi', 'Kgtel', 'K-Mous', 'K-Touch', 'Kenxinda', 'Lava', 'Lenovo', 'LeEco', 'Leago', 'LG', 'M-Horze', 'Meizu', 'Micromax', 'Mione', 'Modu', 'Motorola', 'Microsoft', 'Mi-Tribe', 'MTN', 'Nokia', 'Nomu', 'Olla', 'OnePlus', 'Oppo', 'Oukitel', 'Pano', 'Panasonic', 'Pantech', 'Partnermobile', 'Qmobile', 'Ravoz', 'Razor', 'Realme', 'Santin', 'Sharp', 'Siemens', 'Smartisan', 'Sugar', 'Sony', 'Sony Ericsson', 'Sicco', 'Sowhat', 'Snokor', 'Rocket', 'SQ', 'Swipe Technologies', 'Turaya', 'T-mobile', 'Tesla', 'Ulefone', 'Umidigi', 'Vertu', 'Vivo', 'Vernee', 'Vodafone', 'Wiko', 'Wintouch', 'Xiaomi', 'X-Tigi', 'Yezz', 'ZTE', 'Zopo', 'Others'
            ],

            'computer' => [
                'HP', 'Dell', 'Apple', 'Lenovo', 'Acer', 'Advent', 'Asus', 'Chuwi', 'Fujitsu', 'Gigabyte', 'Hasee', 'i-Life', 'MSI', 'Novatech', 'Razer', 'Samsung', 'Touchmate', 'Zinox', 'Huawei', 'Airis', 'Alienware', 'Avantis', 'Avell', 'Brian', 'Clevo', 'Compaq', 'Cyberpower', 'EduPad', 'Evoo', 'Gateway', 'Geo', 'Google', 'Haier', 'Howard', 'Injoo', 'Linx', 'LG', 'Medion', 'Microsoft', 'Minisonic', 'NEC', 'Omatek', 'One-Netbook', 'Origin PC', 'Packard Bell', 'Panasonic', 'Prime', 'RM Minibook', 'Sager', 'Sony', 'Stone', 'Toshiba', 'Turbo-X', 'Venom', 'Vinovo', 'Vision Computer', 'Xiaomi', 'Others'
            ]
        ];

        foreach ($all_brands as $type => $brands) {
            foreach ($brands as $brand) {
                $brand_exists = Brand::where(
                    [
                        'type' => $type,
                        'name' => $brand,
                        'active_status' => ActiveStatus::ACTIVE
                    ]
                )->exists();

                if (!$brand_exists) {
                    Brand::create(['name' => $brand, 'type' => $type]);
                }
            }
        }

        $ages = [
            'Birth - 24 months',
            '2 - 4 years',
            '5 - 7 years',
            '8 - 13 years',
            'Above 14 years'
        ];

        foreach ($ages as $age) {
            $age_exists = AgeLevel::where(
                [
                    'name' => $age,
                    'active_status' => ActiveStatus::ACTIVE
                ]
            )->exists();

            if (!$age_exists) {
                AgeLevel::create(['name' => $age]);
            }
        }

        $bodies = [
            'Van', 'Station Wagon', 'Sedan', 'SUV', 'Pickup', 'Panel Van', 'Minivan', 'Hatchback', 'Crossover', 'Coupe', 'Convertible', 'Convertible Coupe'
        ];

        foreach ($bodies as $body) {
            $body_exists = Body::where(
                [
                    'name' => $body,
                    'active_status' => ActiveStatus::ACTIVE
                ]
            )->exists();

            if (!$body_exists) {
                Body::create(['name' => $body]);
            }
        }

        $all_conditions = [
            'general' => ['Brand New', 'Used'],
            'vehicle' => ['Nigeria Used', 'Foreign Used'],
            'property' => ['Renovated', 'Old'],
            'electronics' => ['Refurbished']
        ];

        foreach ($all_conditions as $type => $conditions) {
            foreach ($conditions as $condition) {
                $condition_exists = Condition::where(
                    [
                        'type' => $type,
                        'name' => $condition,
                        'active_status' => ActiveStatus::ACTIVE
                    ]
                )->exists();

                if (!$condition_exists) {
                    Condition::create(['type' => $type, 'name' => $condition]);
                }
            }
        }

        $second_conditions = [
            'No faults', 'After Crash', 'Engine Issue', 'First Owner', 'First Registration', 'Gear Issue', 'Need Body Repair', 'Need Repainting', 'Unpainted', 'Original Parts', 'Wiring problems', 'Need Repair'
        ];

        foreach ($second_conditions as $second_condition) {
            $second_condition_exists = SecondCondition::where(
                [
                    'name' => $second_condition,
                    'active_status' => ActiveStatus::ACTIVE
                ]
            )->exists();

            if (!$second_condition_exists) {
                SecondCondition::create(['name' => $second_condition]);
            }
        }

        $fuels = [
            'Petrol / Electric Hybrid', 'Petrol', 'Natural Gas', 'Gasoline', 'Flex-Fuel', 'Electric', 'Diesel / Electric Hybrid', 'Diesel'
        ];

        foreach ($fuels as $fuel) {
            $fuel_exists = Fuel::where(
                [
                    'name' => $fuel,
                    'active_status' => ActiveStatus::ACTIVE
                ]
            )->exists();

            if (!$fuel_exists) {
                Fuel::create(['name' => $fuel]);
            }
        }

        $transmissions = [
            'Automatic', 'Manual'
        ];

        foreach ($transmissions as $transmission) {
            $transmission_exists = Transmission::where(
                [
                    'name' => $transmission,
                    'active_status' => ActiveStatus::ACTIVE
                ]
            )->exists();

            if (!$transmission_exists) {
                Transmission::create(['name' => $transmission]);
            }
        }

        $colours = [
            'Black', 'Silver', 'Gray', 'White', 'Blue', 'Belge', 'Brown', 'Gold', 'Green', 'Orange', 'Pearl', 'Pink', 'Purple', 'Red', 'Yellow', 'Others'
        ];

        foreach ($colours as $colour) {
            $colour_exists = Colour::where(
                [
                    'name' => $colour,
                    'active_status' => ActiveStatus::ACTIVE
                ]
            )->exists();

            if (!$colour_exists) {
                Colour::create(['name' => $colour]);
            }
        }

        $all_prices = [
            'vehicle' => ['Below 1M', '1 - 5M', '5 - 10M', '10 - 15M', 'Above 15M'],
            'property' => ['Below 500k', '500k - 1M', '1M - 3M', '3M - 6M', '6M - 10M', 'Above 10M']
        ];

        foreach ($all_prices as $type => $prices) {
            foreach ($prices as $price) {
                $price_exists = Price::where(
                    [
                        'name' => $price,
                        'type' => $type,
                        'active_status' => ActiveStatus::ACTIVE
                    ]
                )->exists();

                if (!$price_exists) {
                    Price::create(['type' => $type, 'name' => $price]);
                }
            }
        }

        $facilities = [
            'Kitchen Cabinets', 'Tiled Floor', 'Balcony', 'Kitchen Shelf', 'Hot Water', '24 hours electricity', 'Chandelier', 'Dining Area', 'Dish Washer', 'Pop Ceiling', 'Prepaid Meter', 'Refrigerator', 'TV', 'Wardrobe', 'Wi-Fi'
        ];

        foreach ($facilities as $facility) {
            $facility_exists = Facility::where(
                [
                    'name' => $facility,
                    'active_status' => ActiveStatus::ACTIVE
                ]
            )->exists();

            if (!$facility_exists) {
                Facility::create(['name' => $facility]);
            }
        }

        $furnishings = [
            'Furnished', 'Semi-Furnished', 'Unfurnished'
        ];

        foreach ($furnishings as $furnishing) {
            $furnishing_exists = Furnishing::where(
                [
                    'name' => $furnishing,
                    'active_status' => ActiveStatus::ACTIVE
                ]
            )->exists();

            if (!$furnishing_exists) {
                Furnishing::create(['name' => $furnishing]);
            }
        }

        $all_types = [
            'property' => ['Duplex', 'House', 'Maisonette', 'Mansion', 'Shared Apartment', 'Townhouse', 'Villa', 'Flat', 'Bungalow', 'Chalet', 'Condo', 'MiniFlat', 'Farmhouse', 'Penthouse', 'Room & Parlour', 'Studio Apartment'],
            
            'computer' => ['Desktop', 'Laptop'],
        
            'equipment' => ['Speakers', 'Home Theatres', 'Sound Systems', 'Microphones', 'Amplifiers', 'CD Players'],
        
            'furniture' => ['Tables', 'Chairs', 'Sofas', 'TV Stands', 'Beds', 'Armchairs', 'Bag Racks', 'Bar Stools', 'Benches', 'Bed Frames', 'Bookcases', 'Shelfs', 'Cubicles', 'Cabinets', 'Drawers', 'Dressers', 'Electric Fireplaces', 'Headboards', 'Hangers', 'Lockers', 'Mattressess', 'Mirrors'],

            'body' => ['Shower Gels', 'Body Oils', 'Toothpaste', 'Bath Sponges', 'Antiseptics', 'Body Scrubs', 'Body Lotions', 'Dental Floss']
        ];

        foreach ($all_types as $type => $names) {
            foreach ($names as $name) {
                $type_exists = Type::where(
                    [
                        'type' => $type,
                        'name' => $name,
                        'active_status' => ActiveStatus::ACTIVE
                    ]
                )->exists();

                if (!$type_exists) {
                    Type::create(['name' => $name, 'type' => $type]);
                }
            }
        }

        $rams = [
            'Below 512MB', '1 - 4GB', '6GB', '8GB', '12GB'
        ];

        foreach ($rams as $ram) {
            $ram_exists = Ram::where(
                [
                    'name' => $ram,
                    'active_status' => ActiveStatus::ACTIVE
                ]
            )->exists();

            if (!$ram_exists) {
                Ram::create(['name' => $ram]);
            }
        }

        $storage_capacities = [
            'Below 512MB', '1 - 8GB', '8 - 64GB', '64 - 512GB', 'Above 512GB'
        ];

        foreach ($storage_capacities as $storage_capacity) {
            $storage_capacity_exists = StorageCapacity::where(
                [
                    'name' => $storage_capacity,
                    'active_status' => ActiveStatus::ACTIVE
                ]
            )->exists();

            if (!$storage_capacity_exists) {
                StorageCapacity::create(['name' => $storage_capacity]);
            }
        }

        $screen_sizes = [
            'Below 4 inches', '4 - 8 inches', '8 - 15 inches', 'Above 15 inches'
        ];

        foreach ($screen_sizes as $screen_size) {
            $screen_size_exists = ScreenSize::where(
                [
                    'name' => $screen_size,
                    'active_status' => ActiveStatus::ACTIVE
                ]
            )->exists();

            if (!$screen_size_exists) {
                ScreenSize::create(['name' => $screen_size]);
            }
        }

        $materials = [
            'Aluminium', 'Ceramic', 'Copper', 'Faux Leather', 'Genuine Leather', 'Nylon', 'Rubber', 'Stainless Steel'
        ];

        foreach ($materials as $material) {
            $material_exists = Material::where(
                [
                    'name' => $material,
                    'active_status' => ActiveStatus::ACTIVE
                ]
            )->exists();

            if (!$material_exists) {
                Material::create(['name' => $material]);
            }
        }

        $storage_types = [
            'HDD', 'SSD', 'SSHD (Hybrid)'
        ];

        foreach ($storage_types as $storage_type) {
            $storage_type_exists = StorageType::where(
                [
                    'name' => $storage_type,
                    'active_status' => ActiveStatus::ACTIVE
                ]
            )->exists();

            if (!$storage_type_exists) {
                StorageType::create(['name' => $storage_type]);
            }
        }

        $processors = [
            'Intel', 'Intel Core i3', 'Intel Core i5', 'Intel Core i7', 'Intel Core i9', 'Intel Core 2 Duo', 'Intel Core M', 'Intel Core 2 Quad', 'Intel Celeron', 'Intel Pentium', 'Intel Atom', 'Intel Xeon', 'Intel AMD', 'AMD Ryzen', 'AMD A4',  'AMD A6', 'AMD A8', 'AMD A10', 'Nvidia'
        ];

        foreach ($processors as $processor) {
            $processor_exists = Processor::where(
                [
                    'name' => $processor,
                    'active_status' => ActiveStatus::ACTIVE
                ]
            )->exists();

            if (!$processor_exists) {
                Processor::create(['name' => $processor]);
            }
        }

        $operating_systems = [
            'DOS', 'Free DOS',  'Windows XP', 'Windows 7', 'Windows 8', 'Windows 10', 'Linux', 'Ubuntu', 'MacOS'
        ];

        foreach ($operating_systems as $operating_system) {
            $operating_system_exists = OperatingSystem::where(
                [
                    'name' => $operating_system,
                    'active_status' => ActiveStatus::ACTIVE
                ]
            )->exists();

            if (!$operating_system_exists) {
                OperatingSystem::create(['name' => $operating_system]);
            }
        }

        $genders = [
            'Men', 'Women', 'Unisex'
        ];

        foreach ($genders as $gender) {
            $gender_exists = Gender::where(
                [
                    'name' => $gender,
                    'active_status' => ActiveStatus::ACTIVE
                ]
            )->exists();

            if (!$gender_exists) {
                Gender::create(['name' => $gender]);
            }
        }

        $job_types = [
            'Full-time', 'Part-time', 'Temporary', 'Contract', 'Internship'
        ];

        foreach ($job_types as $job_type) {
            $job_type_exists = JobType::where(
                [
                    'name' => $job_type,
                    'active_status' => ActiveStatus::ACTIVE
                ]
            )->exists();

            if (!$job_type_exists) {
                JobType::create(['name' => $job_type]);
            }
        }

        $expected_salaries = [
            'Below 50k', '50k - 100k', '100k - 200k', '200k - 500k', 'Above 500k'
        ];

        foreach ($expected_salaries as $salary) {
            $salary_exists = ExpectedSalary::where(
                [
                    'name' => $salary,
                    'active_status' => ActiveStatus::ACTIVE
                ]
            )->exists();

            if (!$salary_exists) {
                ExpectedSalary::create(['name' => $salary]);
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
        //
    }
}
