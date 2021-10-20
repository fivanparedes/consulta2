<?php
namespace Database\Seeders;

use App\Models\Permission;
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
            'name' => 'Sanatorio Administrador',
            'description' => 'Sanatorio ubicado en [Domicilio]',
            'phone' => 3764123123,
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
            'user_id' => 2,
            'city_id' => 1
        ]);
    
        DB::table('professional_profiles')->insert([
            'licensePlate' => 'M0002',
            'field' => 'Psicología',
            'specialty' => 'Sin especializar',
            'profile_id' => 1,
            'institution_id' => 1
        ]);
        $_perm = Permission::where('name', '_consulta2_institution_profile_perm')->first();
        $_role = Role::where('name', 'Admin')->first();
        $_admin = User::find(1);
        $_role->attachPermission($_perm);
        $_admin->attachRole($_role);
        $_admin->save();
    }
}
