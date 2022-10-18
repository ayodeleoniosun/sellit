<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\State;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $all_cities = [
            'Abia' => [
                'Aba',
                'Arochukwu',
                'Abiriba',
                'Apumiri',
                'Arochukwu',
                'Bende',
                'Nkporo',
                'Ohafia',
                'Umuahia',
            ],
            'Adamawa' => [
                'Belel',
                'Gambe',
                'Jimeta',
                'Mubi',
                'Numan',
                'Yola',
            ],
            'Akwa Ibom' => [
                'Ikot Abasi',
                'Ikot Ekpene',
                'Oron',
                'Uyo',
            ],
            'Anambra' => [
                'Awka',
                'Okpoko',
                'Onitsha',
            ],
            'Bauchi' => [
                'Azare',
                'Bauchi',
                'Jama\'are',
                'Katagum',
                'Misau',
            ],
            'Bayelsa' => [
                'Brass',
            ],
            'Benue' => [
                'Gboko',
                'Makurdi',
                'Otukpo',
            ],
            'Borno' => [
                'Bama',
                'Biu',
                'Dikwa',
                'Kukawa',
                'Maiduguri',
            ],
            'Cross River' => [
                'Calabar',
                'Ogoja',
                'Ugep',
            ],
            'Delta' => [
                'Asaba',
                'Burutu',
                'Koko',
                'Sapele',
                'Ughelli',
                'Warri',
            ],
            'Ebonyi' => [
                'Abakaliki',
            ],
            'Edo' => [
                'Benin City',
                'Uromi',
            ],
            'Ekiti' => [
                'Ado Ekiti',
                'Effom-Alaiye',
                'Ijero',
                'Ikere-Ekiti',
                'Ilawe-Ekiti',
                'Ise-Ekiti',
            ],
            'Enugu' => [
                'Enugu',
                'Nsukka',
            ],
            'FCT (Federal Capital Territory)' => [
                'Abaji',
                'Bwari',
                'Abuja',
                'Gwagwalada',
                'Kuje',
                'Kwali',
            ],
            'Gombe' => [
                'Deba Habe',
                'Gombe',
                'Kumo',
            ],
            'Imo' => [
                'Okigwe',
                'Owerri',
            ],
            'Jigawa' => [
                'Birnin Kudu',
                'Dutse',
                'Garki',
                'Gumel',
                'Hadeija',
                'Kazaure',
            ],
            'Kaduna' => [
                'Jemaa',
                'Kaduna',
                'Zaria',
            ],
            'Kano' => [
                'Kano',
            ],
            'Katsina' => [
                'Daura',
                'Funtua',
                'Katsina',
            ],
            'Kebbi' => [
                'Argungu',
                'Birnin Kebbi',
                'Gwandu',
                'Yelwa',
            ],
            'Kogi' => [
                'Idah',
                'Kabba',
                'Lokoja',
                'Okene',
            ],
            'Kwara' => [
                'Ilorin',
                'Jebba',
                'Lefiagi',
                'Offa',
                'Pategi',
            ],
            'Lagos' => [
                'Badagry',
                'Epe',
                'Ikeja',
                'Ikorodu',
                'Lagos',
                'Mushin',
                'Shomolu',
                'Victoria Island',
            ],
            'Nasarawa' => [
                'Keffi',
                'Lafia',
                'Nasarawa',
            ],
            'Niger' => [
                'Agaie',
                'Baro',
                'Bida',
                'Kontagora',
                'Lapai',
                'Minna',
                'Suleja',
            ],
            'Ogun' => [
                'Abeokuta',
                'Ijebu-Ode',
                'Ilaro',
                'Obafemi-Owode',
                'Sagamu',
            ],
            'Ondo' => [
                'Akure',
                'Ikare',
                'Oka-Akoko',
                'Ondo',
                'Owo',
            ],
            'Osun' => [
                'Ede',
                'Gbongan',
                'Ikire',
                'Ikirun',
                'Ila',
                'Ile-Ife',
                'Ilesa',
                'Ilobu',
                'Inisa',
                'Iwo',
                'Osogbo',
            ],
            'Oyo' => [
                'Ibadan',
                'Igboho',
                'Iseyin',
                'Kisi',
                'Ogbomosho',
                'Oyo',
                'Saki',
            ],
            'Plateau' => [
                'Bukuru',
                'Jos',
                'Vom',
                'Wase',
            ],
            'Rivers' => [
                'Bonny',
                'Buguma',
                'Degema',
                'Eleme',
                'Okrika',
                'Port Harcourt',
            ],
            'Sokoto' => [
                'Sokoto',
            ],
            'Taraba' => [
                'Ibi',
                'Jalingo',
                'Muri',
            ],
            'Yobe' => [
                'Damaturu',
                'Gashua',
                'Bguru',
                'Potiskum',
            ],
            'Zamfara' => [
                'Gusau',
                'Kaura Namoda',
            ],
        ];

        foreach ($all_cities as $state => $cities) {
            $state_id = State::where('name', strtolower($state))->value('id');

            if ($state_id) {
                foreach ($cities as $city) {
                    City::create([
                        'state_id' => $state_id,
                        'name' => $city,
                    ]);
                }
            }
        }
    }
}
