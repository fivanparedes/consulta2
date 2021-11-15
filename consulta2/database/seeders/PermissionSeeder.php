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
            ],
            [
                'name' => 'receive-consults',
                'display_name' => 'Recibir turnos',
                'description' => 'Si el usuario está habilitado a recibir turnos, aparecerá en la pantalla de búsqueda de profesionales.'
            ],
            [
                'name' => 'view-cites',
                'display_name' => 'Consultas: ver',
                'description' => 'Ver lista de consultas, mas no modificarlas'
            ],
            [
                'name' => 'create-cites',
                'display_name' => 'Consultas: crear',
                'description' => 'Agendar turnos desde el lado institucional/profesional'
            ],
            [
                'name' => 'modify-cites',
                'display_name' => 'Consultas: modificar',
                'description' => 'Modificar información de consultas/sesiones ya creadas'
            ],
            [
                'name' => 'delete-cites',
                'display_name' => 'Consultas: borrar',
                'description' => 'Elimina toda la información relacionada a los turnos (no recomendado, mejor cambiarles el estado a Cancelado)'
            ],
            [
                'name' => 'manage-histories',
                'display_name' => 'Administrar historias médicas',
                'description' => 'Puede ver, crear y eliminar historias médicas.'
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