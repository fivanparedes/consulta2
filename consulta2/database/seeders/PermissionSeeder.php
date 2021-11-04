<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\User;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissons = [
            [
                'name' => 'CheckController@index',
                'display_name' => 'Index',
                'description' => 'Check'
            ],
            [
                'name' => 'CheckController@create',
                'display_name' => 'Create',
                'description' => 'Check'
            ],
            [
                'name' => '_consulta2_patient_profile_perm',
                'display_name' => 'Patient Profile',
                'description' => 'These users can register and manage their own calendar'
            ],
            [
                'name' => '_consulta2_professional_profile_perm',
                'display_name' => 'Professional Profile',
                'description' => 'These users can register, manage calendar and see clinical histories'
            ],
            [
                'name' => '_consulta2_institution_profile_perm',
                'display_name' => 'Institution Profile',
                'description' => "It's a special kind of profile that represents an organisation."
            ],
            [
                'name' => 'LifesheetController@index',
                'display_name' => 'Lifesheet: view',
                'description' => 'Can see users lifesheet.'
            ]
        ];

        foreach ($permissons as $key => $value) {

            $permission = Permission::create([
                            'name' => $value['name'],
                            'display_name' => $value['display_name'],
                            'description' => $value['description']
                        ]);

            //User::first()->attachPermission($permission);
        }
    }
}