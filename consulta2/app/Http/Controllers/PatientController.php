<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function info() {
        return view('profile.infoedit');
    }

    public function store() {
        $profile = $this->model->create([
            'user_id' => auth()->user()->id,
            'bornDate' => now(),
            'gender' => 'Mujer',
            'phone' => 3764123123,
            'address' => 'Domicilio',
            'professional_id' => null,
        ]);

        if ($profile) {
            $profile->patientProfile()->create([
                'bornPlace' => 'Ej. Posadas',
                'familyGroup' => 'Ej. Conviviendo con los padres y hermanos',
                'familyPhone' => 3764000000,
                'civilState' => 'Ej: Soltero/a',
                'scholarity' => 'Ej: Universitario en curso',
                'occupation' => 'Ej: Atención al cliente'
            ]);
        }

        return view('profile.infoedit');
    }
}
