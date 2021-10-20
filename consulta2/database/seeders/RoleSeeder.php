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
                'description' => 'Can access all features!'
            ],
            [
                'name' => 'Patient',
                'display_name' => 'Patient',
                'description' => 'Can reserve turns.'
            ],
            [
                'name' => 'Professional',
                'display_name' => 'Professional',
                'description' => 'Can access user histories and turns.'
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
