<?php
namespace Database\Seeders;

use App\Models\BusinessHour;
use App\Models\ConsultType;
use App\Models\Permission;
use App\Models\ProfessionalProfile;
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
            'user_id' => 3,
            'city_id' => 1
        ]);
    
        $_prof_prof = ProfessionalProfile::create([
            'licensePlate' => 'M0002',
            'field' => 'Psicología',
            'specialty' => 'Sin especializar',
            'profile_id' => 1,
            'institution_id' => 1]);
    

        $businesshours = [
            [
                'time' => '08:00'
            ],
            [
                'time' => '09:00'
            ],
            [
                'time' => '10:00'
            ],
            [
                'time' => '11:00'
            ],
            [
                'time' => '16:00'
            ],
            [
                'time' => '17:00'
            ],
            [
                'time' => '18:00'
            ],
            [
                'time' => '19:00'
            ],
        ];
        
        foreach ($businesshours as $key => $value) {
            $hour = BusinessHour::create([
                'time' => $value['time']
            ]);
            $hour->save();
            $_prof_prof->businessHours()->attach($hour->id);
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
        
        $_prof_prof->save();
        
        $_consult1 = ConsultType::where('id', 1)->first();
        $_consult2 = ConsultType::where('id', 2)->first();
        $_consult1->businessHours()->attach([1,2,3,4]);
        $_consult2->businessHours()->attach([5,6,7,8]);
        $_consult1->save();
        $_consult2->save();
    }
}