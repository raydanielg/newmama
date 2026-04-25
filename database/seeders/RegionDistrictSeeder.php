<?php

namespace Database\Seeders;

use App\Models\Region;
use App\Models\District;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegionDistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tanzania Mainland Regions (26 Regions)
        $mainlandRegions = [
            [
                'name' => 'Arusha',
                'districts' => ['Arusha City', 'Arusha DC', 'Meru', 'Karatu', 'Monduli', 'Ngorongoro', 'Longido']
            ],
            [
                'name' => 'Dar es Salaam',
                'districts' => ['Ilala', 'Kinondoni', 'Temeke', 'Ubungo', 'Kigamboni']
            ],
            [
                'name' => 'Dodoma',
                'districts' => ['Dodoma City', 'Dodoma DC', 'Kondoa', 'Kongwa', 'Mpwapwa', 'Chamwino', 'Bahi']
            ],
            [
                'name' => 'Geita',
                'districts' => ['Geita Town', 'Geita DC', 'Bukombe', 'Chato', 'Mbogwe', 'Nyang\'hwale']
            ],
            [
                'name' => 'Iringa',
                'districts' => ['Iringa DC', 'Iringa Urban', 'Kilolo', 'Mafinga Town', 'Mufindi']
            ],
            [
                'name' => 'Kagera',
                'districts' => ['Bukoba DC', 'Bukoba Municipal', 'Karagwe', 'Kyerwa', 'Missenyi', 'Muleba', 'Ngara', 'Biharamulo']
            ],
            [
                'name' => 'Katavi',
                'districts' => ['Mpanda DC', 'Mpanda Town', 'Nsimbo', 'Mlele']
            ],
            [
                'name' => 'Kigoma',
                'districts' => ['Kigoma DC', 'Kigoma Town', 'Kasulu Town', 'Kasulu DC', 'Kibondo', 'Kakonko', 'Uvinza', 'Buhigwe']
            ],
            [
                'name' => 'Kilimanjaro',
                'districts' => ['Moshi DC', 'Moshi Urban', 'Hai', 'Rombo', 'Siha', 'Mwanga', 'Same']
            ],
            [
                'name' => 'Lindi',
                'districts' => ['Lindi DC', 'Lindi Municipal', 'Kilwa', 'Ruangwa', 'Liwale', 'Nachingwea']
            ],
            [
                'name' => 'Mara',
                'districts' => ['Musoma DC', 'Musoma Municipal', 'Bunda DC', 'Bunda Town', 'Serengeti', 'Tarime', 'Rorya', 'Butiama']
            ],
            [
                'name' => 'Mbeya',
                'districts' => ['Mbeya City', 'Mbeya DC', 'Rungwe', 'Kyela', 'Mbarali', 'Ileje', 'Mbozi', 'Busokelo', 'Chunya']
            ],
            [
                'name' => 'Morogoro',
                'districts' => ['Morogoro Urban', 'Morogoro DC', 'Mvomero', 'Kilombero', 'Ulanga', 'Malinyi', 'Gairo', 'Mlimba']
            ],
            [
                'name' => 'Mtwara',
                'districts' => ['Mtwara DC', 'Mtwara Urban', 'Masasi Town', 'Masasi DC', 'Newala', 'Tandahimba', 'Nanyumbu']
            ],
            [
                'name' => 'Mwanza',
                'districts' => ['Mwanza City', 'Ilemela', 'Nyamagana', 'Magu', 'Misungwi', 'Kwimba', 'Sengerema', 'Ukerewe', 'Buchosa']
            ],
            [
                'name' => 'Njombe',
                'districts' => ['Njombe Town', 'Njombe DC', 'Ludewa', 'Makete', 'Wanging\'ombe']
            ],
            [
                'name' => 'Pwani',
                'districts' => ['Kibaha Town', 'Kibaha DC', 'Bagamoyo', 'Rufiji', 'Kisarawe', 'Mkuranga', 'Mafia', 'Mkushi']
            ],
            [
                'name' => 'Rukwa',
                'districts' => ['Sumbawanga DC', 'Sumbawanga Town', 'Nkasi', 'Kalambo']
            ],
            [
                'name' => 'Ruvuma',
                'districts' => ['Songea Municipal', 'Songea DC', 'Namtumbo', 'Tunduru', 'Mbinga', 'Nyasa', 'Madaba']
            ],
            [
                'name' => 'Shinyanga',
                'districts' => ['Shinyanga Municipal', 'Shinyanga DC', 'Kahama Town', 'Kahama DC', 'Kishapu']
            ],
            [
                'name' => 'Simiyu',
                'districts' => ['Bariadi Town', 'Bariadi DC', 'Maswa', 'Itilima', 'Meatu', 'Busega']
            ],
            [
                'name' => 'Singida',
                'districts' => ['Singida DC', 'Singida Urban', 'Iramba', 'Manyoni', 'Ikungi', 'Mkalama']
            ],
            [
                'name' => 'Songwe',
                'districts' => ['Vwawa Town', 'Mbozi DC', 'Tunduma Town', 'Ileje', 'Momba']
            ],
            [
                'name' => 'Tabora',
                'districts' => ['Tabora Municipal', 'Tabora DC', 'Urambo', 'Uyui', 'Sikonge', 'Kaliua', 'Nzega', 'Igunga', 'Ushetu']
            ],
            [
                'name' => 'Tanga',
                'districts' => ['Tanga City', 'Muheza', 'Korogwe Town', 'Korogwe DC', 'Lushoto', 'Pangani', 'Handeni', 'Kilindi', 'Mkinga']
            ],
            [
                'name' => 'Manyara',
                'districts' => ['Babati DC', 'Babati Town', 'Mbulu', 'Hanang', 'Kiteto', 'Simanjiro', 'Mbugani']
            ],
        ];

        // Zanzibar Regions (5 Regions)
        $zanzibarRegions = [
            [
                'name' => 'Kaskazini Pemba',
                'districts' => ['Wete', 'Michewani']
            ],
            [
                'name' => 'Kusini Pemba',
                'districts' => ['Chake Chake', 'Mkoani']
            ],
            [
                'name' => 'Kaskazini Unguja',
                'districts' => ['Kaskazini A', 'Kaskazini B']
            ],
            [
                'name' => 'Kusini Unguja',
                'districts' => ['Kusini', 'Kati']
            ],
            [
                'name' => 'Mjini Magharibi',
                'districts' => ['Mjini', 'Magharibi A', 'Magharibi B']
            ],
        ];

        // Insert Mainland Regions
        foreach ($mainlandRegions as $region) {
            $regionModel = \App\Models\Region::updateOrCreate(
                ['name' => $region['name']],
                ['created_at' => now(), 'updated_at' => now()]
            );

            foreach ($region['districts'] as $district) {
                \App\Models\District::updateOrCreate(
                    ['name' => $district, 'region_id' => $regionModel->id],
                    ['created_at' => now(), 'updated_at' => now()]
                );
            }
        }

        // Insert Zanzibar Regions
        foreach ($zanzibarRegions as $region) {
            $regionModel = \App\Models\Region::updateOrCreate(
                ['name' => $region['name']],
                ['created_at' => now(), 'updated_at' => now()]
            );

            foreach ($region['districts'] as $district) {
                \App\Models\District::updateOrCreate(
                    ['name' => $district, 'region_id' => $regionModel->id],
                    ['created_at' => now(), 'updated_at' => now()]
                );
            }
        }
    }
}
