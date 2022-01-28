<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Laratrust;
use Illuminate\Support\Facades\DB;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'name' => 'Admin',
                'display_name' => 'Admin',
                'description' => 'Todo lo puede'
            ],
            [
                'name' => 'Institution',
                'display_name' => 'Institución',
                'description' => 'Persona que puede asociar profesionales y controlar flujo de agendamiento.'
            ],
            [
                'name' => 'Patient',
                'display_name' => 'Paciente',
                'description' => 'Puede reservar turnos.'
            ],
            [
                'name' => 'Professional',
                'display_name' => 'Prestador',
                'description' => 'Puede gestionar su agenda e historias médicas.'
            ]
        ];
        foreach ($roles as $key => $value) {
            $role = Role::create([
                'name' => $value['name'],
                'display_name' => $value['display_name'],
                'description' => $value['description']
            ]);
        }
    }
}