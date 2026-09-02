<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class UpdateBarberServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'name'        => 'Haircut DEWASA (Senin - Jumat)',
                'description' => 'Potong rambut pria dewasa - Senin s/d Jumat (Weekday)',
                'price'       => 30000,
            ],
            [
                'name'        => 'Haircut DEWASA (Sabtu - Minggu)',
                'description' => 'Potong rambut pria dewasa - Sabtu & Minggu (Weekend)',
                'price'       => 35000,
            ],
            [
                'name'        => 'Haircut ANAK (Senin - Jumat)',
                'description' => 'Potong rambut anak - Senin s/d Jumat (Weekday)',
                'price'       => 25000,
            ],
            [
                'name'        => 'Haircut ANAK (Sabtu - Minggu)',
                'description' => 'Potong rambut anak - Sabtu & Minggu (Weekend)',
                'price'       => 30000,
            ],
            [
                'name'        => 'Hair Coloring - Highlight',
                'description' => 'Pewarnaan rambut highlight',
                'price'       => 130000,
            ],
            [
                'name'        => 'Hair Coloring - Full Colour',
                'description' => 'Pewarnaan rambut full colour',
                'price'       => 200000,
            ],
            [
                'name'        => 'Hair Coloring - Bleaching',
                'description' => 'Proses bleaching rambut',
                'price'       => 110000,
            ],
            [
                'name'        => 'Perawatan - Creambath',
                'description' => 'Perawatan creambath rambut dan relaksasi',
                'price'       => 60000,
            ],
            [
                'name'        => 'Perawatan - Masker Rambut',
                'description' => 'Perawatan hair mask / masker rambut bernutrisi',
                'price'       => 100000,
            ],
        ];

        // Update ID 1 & 2 if exist to retain booking references cleanly
        $cukurAnak = Service::where('name', 'Cukur Anak')->orWhere('id', 1)->first();
        if ($cukurAnak) {
            $cukurAnak->update($services[2]); // Haircut Anak (Senin - Jumat)
        }

        $cukurDewasa = Service::where('name', 'Cukur Dewasa')->orWhere('id', 2)->first();
        if ($cukurDewasa) {
            $cukurDewasa->update($services[0]); // Haircut Dewasa (Senin - Jumat)
        }

        // Insert or update the rest
        foreach ($services as $srv) {
            Service::updateOrCreate(
                ['name' => $srv['name']],
                [
                    'description' => $srv['description'],
                    'price'       => $srv['price'],
                ]
            );
        }
    }
}
