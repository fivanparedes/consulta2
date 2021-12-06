<?php

namespace Database\Seeders;

use App\Models\BusinessHour;
use App\Models\City;
use App\Models\ConsultType;
use App\Models\Coverage;
use App\Models\Lifesheet;
use App\Models\MedicalHistory;
use App\Models\PatientProfile;
use App\Models\Permission;
use App\Models\ProfessionalProfile;
use App\Models\Profile;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'dni' => 12345678,
            'name' => 'Administrador',
            'lastname' => 'del Sistema',
            'email' => 'admin@lightbp.com',
            'email_verified_at' => now(),
            'password' => Hash::make('secret'),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('institution_profiles')->insert([
            'user_id' => 1,
            'name' => 'Independiente',
            'description' => 'La persona que ejerce el rol profesional tiene su propio consultorio, o es autónom@.',
            'phone' => 3764123123,
            'address' => '[Domicilio del profesional]',
            'city_id' => 1
        ]);

        DB::table('users')->insert([
            'dni' => 123,
            'name' => 'Administrador',
            'lastname' => 'del Patito',
            'email' => 'sanatoriopatito@consulta2.com',
            'email_verified_at' => now(),
            'password' => Hash::make('secret'),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('institution_profiles')->insert([
            'user_id' => 2,
            'name' => 'Sanatorio Patito',
            'description' => 'Centro de salud dedicado a la atención psicológica y fonoaudiológica.',
            'phone' => 3764123456,
            'address' => 'Calle Wallaby 42 Sydney',
            'city_id' => 1
        ]);

        //Professional example
        DB::table('users')->insert([
            'dni' => 987654,
            'name' => 'Nombre del',
            'lastname' => 'Profesional',
            'email' => 'psico@consulta2.com',
            'email_verified_at' => now(),
            'password' => Hash::make('secret'),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('profiles')->insert([
            'bornDate' => date_create('20/01/1996'),
            'gender' => 'Femenino',
            'address' => 'Chacra 500 calle 100',
            'phone' => 3765121212,
            'user_id' => 3,
            'city_id' => 1
        ]);

        $_prof_prof = ProfessionalProfile::create([
            'licensePlate' => 'M0002',
            'field' => 'general',
            'specialty_id' => 1,
            'profile_id' => 1,
            'institution_id' => 1
        ]);

        /**
         * Paciente de ejemplo
        */
        $posadas = City::find(1);
        $user_patient = User::create([
            'dni' => 949494,
            'name' => 'Nombre del',
            'lastname' => 'Paciente',
            'email' => 'paciente@consulta2.com',
            'email_verified_at' => now(),
            'password' => Hash::make('secret'),
        ]);

        $profile_patient = Profile::create([
            'bornDate' => date_create('29/01/1999'),
            'gender' => 'Masculino',
            'address' => '75C',
            'phone' => 3764299742,
            'user_id' => $user_patient->id,
            'city_id' => $posadas->id
        ]);

        $patient = PatientProfile::create([
            'bornPlace' => $posadas->name,
            'familyGroup' => 'Convive con padres y hermanos',
            'familyPhone' => 4466666,
            'civilState' => 'Soltero',
            'scholarity' => 'Universitario en curso',
            'occupation' => 'Programador',
            'profile_id' => $profile_patient->id
        ]);

        $lifesheet_patient = Lifesheet::create([
            'diseases' => 'Ninguna',
            'surgeries' => 'Ninguna',
            'medication' => 'Ninguna',
            'allergies' => 'Ninguna',
            'smokes' => 0,
            'drinks' => 2,
            'exercises' => 1,
            'hceu' => 'AA00001',
            'patient_profile_id' => $patient->id,
            'coverage_id' => 2
        ]);

        $history_patient = MedicalHistory::create([
            'indate' => now(),
            'psicological_history' => encrypt('Es programador y está medio quemado de la cabeza'),
            'visitreason' => encrypt('Estrés por el trabajo'),
            'diagnosis' => encrypt('Va progresando con el paso del tiempo'),
            'clinical_history' => encrypt('Historia médica...'),
            'patient_profile_id' => $patient->id,
            'professional_profile_id' => 1
        ]);

        $role_patient = Role::where('name', 'Patient')->first();
        $user_patient->attachRole($role_patient);
        $perm_patient = Permission::where('name', '_consulta2_patient_profile_perm')->first();
        $user_patient->attachPermission($perm_patient);
        $user_patient->save();


        $businesshours = [
            [
                'time' => '08:00'
            ],
            [
                'time' => '08:30'
            ],
            [
                'time' => '08:45'
            ],
            [
                'time' => '09:00'
            ],
            [
                'time' => '09:30'
            ],
            [
                'time' => '09:45'
            ],
            [
                'time' => '10:00'
            ],
            [
                'time' => '10:30'
            ],
            [
                'time' => '10:45'
            ],
            [
                'time' => '11:00'
            ],
            [
                'time' => '11:30'
            ],
            [
                'time' => '11:45'
            ],
            [
                'time' => '16:00'
            ],
            [
                'time' => '16:30'
            ],
            [
                'time' => '16:45'
            ],
            [
                'time' => '17:00'
            ],
            [
                'time' => '17:30'
            ],
            [
                'time' => '17:45'
            ],
            [
                'time' => '18:00'
            ],
            [
                'time' => '18:30'
            ],
            [
                'time' => '19:00'
            ],
            [
                'time' => '19:30'
            ],
            [
                'time' => '19:45'
            ],
        ];

        $consult_types = [
            [
                'name' => 'Primera entrevista',
                'availability' => '1;2;3;4',
                'requires_auth' => false,
                'visible' => true,
                 'professional_profile_id' => 1
            ],
            [
                'name' => 'Sesión de terapia',
                'availability' => '1;2;3;4;5',
                'requires_auth' => true,
                'visible' => true,
                'professional_profile_id' => 1
            ]
        ];

        foreach ($businesshours as $key => $value) {
            $hour = BusinessHour::create([
                'time' => $value['time']
            ]);
            $hour->save();
            $_prof_prof->businessHours()->attach($hour->id);
        }

        foreach ($consult_types as $key => $value) {
            $consult_type = ConsultType::create([
                'name' => $value['name'],
                'availability' => $value['availability'],
                'professional_profile_id' => $value['professional_profile_id']
            ]);
        }

        $_perm = Permission::where('name', '_consulta2_institution_profile_perm')->first();
        $_perm2 = Permission::where('name', '_consulta2_professional_profile_perm')->first();
        $_role = Role::where('name', 'Admin')->first();
        $_role2 = Role::where('name', 'Professional')->first();
        $_admin = User::find(1);
        $_inst = User::find(2);
        $_prof = User::find(3);
        $_role->attachPermission($_perm);
        $_role->save();
        $_admin->attachRole($_role);
        $_admin->save();
        $_inst->attachRole($_role);
        $_inst->save();
        $_role2->attachPermission($_perm2);
        $_role2->save();
        $_prof->attachRole($_role2);
        $_prof->save();

        $_coverage1 = Coverage::find(1);
        $_coverage2 = Coverage::find(2);
        $_prof_prof->coverages()->attach($_coverage1->id);
        $_prof_prof->coverages()->attach($_coverage2->id);
        $_prof_prof->save();

        $_consult1 = ConsultType::where('id', 1)->first();
        $_consult2 = ConsultType::where('id', 2)->first();
        $_consult1->businessHours()->attach([1, 2, 3, 4]);
        $_consult1->practices()->attach(1);
        $_consult2->businessHours()->attach([5, 6, 7, 8]);
        $_consult2->practices()->attach([2,3,4,5]);
        $_consult1->save();
        $_consult2->save();
    }
}