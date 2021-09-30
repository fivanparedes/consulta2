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
        DB::table('users')->insert([
            'dni' => 12345678,
            'name' => 'Nombre del',
            'lastname' => 'Profesional',
            'email' => 'admin@lightbp.com',
            'email_verified_at' => now(),
            'password' => Hash::make('secret'),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('profiles')->insert([
            'user_id' => 1,
            'bornDate' => date('d-m-Y', strtotime('10/07/1996')),
            'gender' => 'Mujer',
            'phone' => 3764123123,
            'address' => 'Calle Wallaby 42 Sydney',
            'patient_id' => null,
            'professional_id' => 1
        ]);
        DB::table('professional_profiles')->insert([
            'licensePlate' => 'M0001',
            'field' => 'Psicología',
            'specialty' => 'Sin especializar',
            'parentprofile_id' => 1
        ]);
    }
}
