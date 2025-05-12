<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Province;
use Illuminate\Database\Seeder;
use Kavist\RajaOngkir\Facades\RajaOngkir;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fakeProvinces = [
            [
                'province_id' => 1,
                'province' => 'Bali',
                'cities' => [
                    [
                        'city_name' => 'Denpasar',
                        'type' => 'Kota',
                        'postal_code' => '80111'
                    ]
                ]
            ],
            [
                'province_id' => 2,
                'province' => 'Jawa Timur',
                'cities' => [
                    [
                        'city_name' => 'Surabaya',
                        'type' => 'Kota',
                        'postal_code' => '60111'
                    ],
                    [
                        'city_name' => 'Malang',
                        'type' => 'Kota',
                        'postal_code' => '65111'
                    ]
                ]
            ]
        ];

        foreach ($fakeProvinces as $province) {
            $provinceResult = Province::create(['name' => $province['province']]);

            foreach ($province['cities'] as $city) {
                City::create([
                    'province_id' => $provinceResult->id,
                    'name' => $city['city_name'],
                    'type' => $city['type'],
                    'postal_code' => $city['postal_code']
                ]);
            }
        }
    }
}
