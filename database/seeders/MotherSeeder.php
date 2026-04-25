<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Mother;
use App\Models\Country;
use App\Models\Region;
use App\Models\District;

class MotherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvPath = public_path('malkia_members_export_2026-04-06.csv');
        
        if (!file_exists($csvPath)) {
            $this->command->error("CSV file not found at: {$csvPath}");
            return;
        }

        $file = fopen($csvPath, 'r');
        fgetcsv($file); // Skip header

        $tzCountryId = Country::where('iso2', 'TZ')->value('id');
        $defaultRegionId = Region::first()?->id ?: 1;
        $defaultDistrictId = District::first()?->id ?: 1;

        $count = 0;
        while (($row = fgetcsv($file)) !== false) {
            // MK Number, Full Name, Phone, Journey Stage, Weeks, Date of Joining, Approved Date, Hospital Planned
            if (count($row) < 3) continue;

            $mkNumber = trim($row[0]);
            $fullName = trim($row[1]);
            $phoneRaw = trim($row[2]);
            $stageRaw = strtolower(trim($row[3]));
            $weeks = trim($row[4]);
            $joiningDate = trim($row[5]);
            $approvedDate = trim($row[6]);
            $hospital = trim($row[7]);

            // Safisha namba ya simu (kuhakikisha inaanza na +255 au 0...)
            $phone = $phoneRaw;
            if (str_starts_with($phone, '7') || str_starts_with($phone, '6')) {
                $phone = '0' . $phone;
            }
            if (str_starts_with($phone, '255')) {
                $phone = '+' . $phone;
            }

            // Map journey stage kulingana na 'status' ya Mother model
            $status = 'pregnant';
            if (str_contains($stageRaw, 'post') || str_contains($stageRaw, 'parent') || str_contains($stageRaw, 'kujifungua')) {
                $status = 'new_parent';
            } elseif (str_contains($stageRaw, 'ttc') || str_contains($stageRaw, 'trying') || str_contains($stageRaw, 'kutafuta')) {
                $status = 'trying';
            }

            // Piga hesabu ya EDD (Estimated Delivery Date)
            $eddDate = null;
            if ($status === 'pregnant' && is_numeric($weeks)) {
                $weeksInt = (int)$weeks;
                // Mimba ni wiki 40
                $remainingWeeks = 40 - $weeksInt;
                $eddDate = now()->addWeeks($remainingWeeks)->format('Y-m-d');
            }

            Mother::updateOrCreate(
                ['whatsapp_number' => $phone],
                [
                    'mk_number' => $mkNumber ?: null,
                    'full_name' => $fullName,
                    'country_id' => $tzCountryId,
                    'region_id' => $defaultRegionId,
                    'district_id' => $defaultDistrictId,
                    'status' => $status,
                    'is_approved' => !empty($approvedDate),
                    'approved_at' => !empty($approvedDate) ? (\Illuminate\Support\Carbon::parse($approvedDate)->isValid() ? $approvedDate : now()) : null,
                    'edd_date' => $eddDate,
                    'baby_age' => ($status === 'new_parent' && is_numeric($weeks)) ? (int)$weeks : null,
                    'current_step' => '3',
                    'created_at' => \Illuminate\Support\Carbon::parse($joiningDate)->isValid() ? $joiningDate : now(),
                    'metadata' => [
                        'source' => 'csv_import',
                        'hospital_planned' => $hospital ?: 'Not specified',
                        'original_csv_data' => [
                            'stage' => $stageRaw,
                            'weeks' => $weeks,
                            'joining' => $joiningDate
                        ]
                    ]
                ]
            );
            $count++;
        }

        fclose($file);
        $this->command->info("Seeded {$count} mothers from CSV.");
    }
}
