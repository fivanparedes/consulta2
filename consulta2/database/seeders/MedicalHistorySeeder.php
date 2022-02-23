<?php

namespace Database\Seeders;

use App\Models\BusinessHour;
use App\Models\MedicalHistory;
use App\Models\ProfessionalProfile;
use Illuminate\Database\Seeder;

class MedicalHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $business_hours = BusinessHour::all();
        $professional_profiles = ProfessionalProfile::all();
        foreach ($professional_profiles as $professional_profile) {
            if (count($professional_profile->businessHours) == 0) {
                $professional_profile->businessHours()->attach($business_hours);
                $professional_profile->save();
            }
        }
    }
}
