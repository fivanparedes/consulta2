<?php
namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'id' => 1,
            'name' => 'Admin',
            'description' => 'User who has the absolute power in the system. Use with caution!'
        ]);
        DB::table('roles')->insert([
            'id' => 2,
            'name' => 'Professional',
            'description' => 'User who has the most privileges in the system including patient info, except audit and advanced config.'
        ]);
        DB::table('roles')->insert([
            'id' => 3,
            'name' => 'Secretary',
            'description' => 'User who can manage the agenda but not the patient info.'
        ]);
        DB::table('roles')->insert([
            'id' => 4,
            'name' => 'Patient',
            'description' => 'User who can only register and manage their own agenda.'
        ]);
        DB::table('users')->insert([
            'dni' => 12345678,
            'name' => 'Nombre del',
            'lastname' => 'Administrador',
            'email' => 'admin@lightbp.com',
            'email_verified_at' => now(),
            'password' => Hash::make('secret'),
            'profile_id' => 1,
            'role_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        DB::table('profiles')->insert([
            'user_id' => 1,
            'bornDate' => date('d-m-Y', strtotime('10/07/1996')),
            'gender' => 'Mujer',
            'phone' => 3764123123,
            'address' => 'Calle Wallaby 42 Sydney',
            'patient_profile_id' => null,
            'professional_profile_id' => 1
        ]);
        DB::table('professional_profiles')->insert([
            'licensePlate' => 'M0001',
            'field' => 'Psicología',
            'specialty' => 'Sin especializar',
            'profile_id' => 1
        ]);

        //Professional example
        DB::table('users')->insert([
            'dni' => 987654,
            'name' => 'Nombre del',
            'lastname' => 'Profesional',
            'email' => 'psico@consulta2.com',
            'email_verified_at' => now(),
            'password' => Hash::make('secret'),
            'profile_id' => 2,
            'role_id' => 2,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        DB::table('profiles')->insert([
            'user_id' => 2,
            'bornDate' => date('d-m-Y', strtotime('10/07/1996')),
            'gender' => 'Mujer',
            'phone' => 3764123123,
            'address' => 'Domicilio del consultorio',
            'patient_profile_id' => null,
            'professional_profile_id' => 2
        ]);
        DB::table('professional_profiles')->insert([
            'licensePlate' => 'M0002',
            'field' => 'Psicología',
            'specialty' => 'Sin especializar',
            'profile_id' => 2
        ]);
    }
}
