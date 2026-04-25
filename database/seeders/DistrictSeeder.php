<?php

namespace Database\Seeders;

use App\Models\Region;
use App\Models\District;
use Illuminate\Database\Seeder;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $districts = [
            'Arusha' => ['Arusha Mjini', 'Arusha Vijijini', 'Karatu', 'Longido', 'Meru', 'Monduli', 'Ngorongoro'],
            'Dar es Salaam' => ['Ilala', 'Kinondoni', 'Temeke', 'Ubungo', 'Kigamboni'],
            'Dodoma' => ['Dodoma City', 'Bahi', 'Chamwino', 'Chemba', 'Kondoa', 'Kongwa', 'Mpwapwa'],
            'Geita' => ['Geita Town', 'Bukombe', 'Chato', 'Geita', 'Mbogwe', 'Nyang\'hwale'],
            'Iringa' => ['Iringa Municipal', 'Iringa DC', 'Kilolo', 'Mafinga Town', 'Mufindi'],
            'Kagera' => ['Bukoba Municipal', 'Bukoba DC', 'Biharamulo', 'Chato', 'Karagwe', 'Kyerwa', 'Missenyi', 'Muleba', 'Ngara'],
            'Katavi' => ['Mpanda Municipal', 'Mpanda DC', 'Mlele', 'Nsimbo'],
            'Kigoma' => ['Kigoma Municipal', 'Kigoma DC', 'Kakonko', 'Kasulu Town', 'Kasulu DC', 'Kibondo', 'Kibiti', 'Uvinza'],
            'Kilimanjaro' => ['Moshi Municipal', 'Moshi DC', 'Hai', 'Mwanga', 'Rombo', 'Same', 'Siha'],
            'Lindi' => ['Lindi Municipal', 'Lindi DC', 'Kilwa', 'Liwale', 'MTama', 'Nachingwea', 'Ruangwa'],
            'Manyara' => ['Babati Town', 'Babati DC', 'Hanang', 'Kiteto', 'Mbulu', 'Mbulu Town', 'Simanjiro'],
            'Mara' => ['Musoma Municipal', 'Musoma DC', 'Bunda Town', 'Bunda DC', 'Butiama', 'Rorya', 'Serengeti', 'Tarime'],
            'Mbeya' => ['Mbeya City', 'Mbeya DC', 'Chunya', 'Kyela', 'Mbarali', 'Mbozi', 'Rungwe'],
            'Morogoro' => ['Morogoro Municipal', 'Morogoro DC', 'Gairo', 'Kilombero', 'Kilosa', 'Mvomero', 'Ulanga'],
            'Mtwara' => ['Mtwara Municipal', 'Mtwara DC', 'Masasi Town', 'Masasi DC', 'Newala Town', 'Newala DC', 'Nanyumbu', 'Tandahimba'],
            'Mwanza' => ['Mwanza City', 'Ilemela', 'Kwimba', 'Magu', 'Misungwi', 'Buchosa', 'Sengerema', 'Ukerewe'],
            'Njombe' => ['Njombe Town', 'Njombe DC', 'Ludewa', 'Makambako Town', 'Makete', 'Wanging\'ombe'],
            'Pemba Kaskazini' => ['Wete', 'Michewani'],
            'Pemba Kusini' => ['Chake Chake', 'Mkoani'],
            'Pwani' => ['Kibaha Town', 'Kibaha DC', 'Bagamoyo', 'Kisarawe', 'Mafia', 'Mkuranga', 'Rufiji', 'Vikindu'],
            'Rukwa' => ['Sumbawanga Municipal', 'Sumbawanga DC', 'Kalambo', 'Kansi', 'Mpanda', 'Nkasi'],
            'Ruvuma' => ['Songea Municipal', 'Songea DC', 'Mbinga Town', 'Mbinga DC', 'Namtumbo', 'Njombe', 'Tunduru'],
            'Shinyanga' => ['Shinyanga Municipal', 'Shinyanga DC', 'Kahama Town', 'Kahama DC', 'Kishapu', 'Meatu'],
            'Simiyu' => ['Bariadi Town', 'Bariadi DC', 'Busega', 'Itilima', 'Maswa', 'Meatu'],
            'Singida' => ['Singida Municipal', 'Singida DC', 'Iramba', 'Ikungi', 'Manyoni', 'Mkalama', 'Miti'],
            'Songwe' => ['Vwawa Town', 'Mbozi', 'Ileje', 'Momba', 'Tunduma Town'],
            'Tabora' => ['Tabora Municipal', 'Tabora DC', 'Igunga', 'Kaliua', 'Nzega Town', 'Nzega DC', 'Sikonge', 'Urambo', 'Uyui'],
            'Tanga' => ['Tanga City', 'Tanga DC', 'Handeni Town', 'Handeni DC', 'Korogwe Town', 'Korogwe DC', 'Lushoto', 'Mkinga', 'Muheza', 'Mlalo', 'Pangani'],
            'Unguja Kaskazini' => ['Kaskazini A', 'Kaskazini B'],
            'Unguja Kusini' => ['Kusini', 'Kati'],
            'Unguja Mjini Magharibi' => ['Magharibi A', 'Magharibi B'],
        ];

        foreach ($districts as $regionName => $districtList) {
            $region = Region::where('name', $regionName)->first();
            if ($region) {
                foreach ($districtList as $districtName) {
                    District::updateOrCreate(
                        ['name' => $districtName, 'region_id' => $region->id],
                        ['name' => $districtName, 'region_id' => $region->id]
                    );
                }
            }
        }

        $this->command->info('Districts seeded successfully!');
    }
}
