<?php

namespace Database\Seeders;

use App\Models\ConsultType;
use App\Models\Coverage;
use App\Models\Nomenclature;
use App\Models\Practice;
use App\Models\Price;
use App\Models\Specialty;

use Illuminate\Database\Seeder;
class PracticesSeeder extends Seeder {
    public function run() {
        $coverages = [
            [
                'name' => 'Particular',
                'address' => 'Domicilio del paciente',
                'phone' => 'Número del paciente',
                'city_id' => 1
            ],
            [
                'name' => 'OSPAT ConSalud',
                'address' => 'Tucumán 2143. Posadas, Misiones.',
                'phone' => '+543764437812',
                'city_id' => 1
            ],
            [
                'name' => 'Instituto de Previsión Social (IPS)',
                'address' => 'Bolívar 2152. Posadas, Misiones.',
                'phone' => '+543764448673',
                'city_id' => 1
            ],
            [
                'name' => 'Galeno',
                'address' => 'Vuelta de Obligado 2225 CABA',
                'phone' => '0-810-999',
                'city_id' => 1
            ],
            [
                'name' => 'Poder Judicial de la Nación',
                'address' => '',
                'phone' => '',
                'city_id' => 1
            ],
            [
                'name' => 'IOSFA (IOSE - DIBA - DIBPFA)',
                'address' => '',
                'phone' => '',
                'city_id' => 1
            ],
            [
                'name' => 'MEDIFE',
                'address' => '',
                'phone' => '',
                'city_id' => 1
            ],
            [
                'name' => 'Swiss Medical',
                'address' => '',
                'phone' => '',
                'city_id' => 1
            ],
            [
                'name' => 'APTM (Tabacaleros y Mutual)',
                'address' => '',
                'phone' => '',
                'city_id' => 1
            ],
            [
                'name' => 'Jerárquicos Salud',
                'address' => '',
                'phone' => '',
                'city_id' => 1
            ],
            [
                'name' => 'IPS',
                'address' => '',
                'phone' => '',
                'city_id' => 1
            ],
            [
                'name' => 'Prevención Salud S.A',
                'address' => '',
                'phone' => '',
                'city_id' => 1
            ],
            [
                'name' => 'SANCOR Salud',
                'address' => '',
                'phone' => '',
                'city_id' => 1
            ],
            [
                'name' => 'OSDOP',
                'address' => '',
                'phone' => '',
                'city_id' => 1
            ],
            [
                'name' => 'SMAUNAM',
                'address' => '',
                'phone' => '',
                'city_id' => 1
            ],
        ];

        $nomenclatures = [
            [
                'code' => '00000',
                'description' => 'SIN NOMENCLATURA - PRACTICA CUALQUIERA',
                'specialty_id' => 1
            ],
            [
                'code' => '00001',
                'description' => 'PRIMERA ENTREVISTA',
                'specialty_id' => 1
            ],
            [
                'code' => '00002',
                'description' => 'PSICOTERAPIA INDIVIDUAL',
                'specialty_id' => 1
            ],
            [
                'code' => '00003',
                'description' => 'PSICOTERAPIA GRUPAL',
                'specialty_id' => 1
            ],
            [
                'code' => '00004',
                'description' => 'PSICODIAGNÓSTICO',
                'specialty_id' => 1
            ],
            [
                'code' => '00005',
                'description' => 'PSICOPROFILAXIS',
                'specialty_id' => 1
            ],
            [
                'code' => '00006',
                'description' => 'ORIENTACIÓN',
                'specialty_id' => 1
            ]
        ];

        $practices = [
            /**
             * Practicas galeno
             */
            [
                'name' => 'Primera entrevista',
                'description' => 'Primera entrevista que se hace al paciente.',
                'maxtime' => 60,
                'allowed_modes' => 2,
                'nomenclature_id' => 1,
                'coverage_id' => 1
            ],
            [
                'name' => 'Psicoterapia Individual',
                'description' => 'Sesión de terapia de una sola persona',
                'maxtime' => 40,
                'allowed_modes' => 2,
                'nomenclature_id' => 2,
                'coverage_id' => 1
            ],
            [
                'name' => 'Psicoterapia Grupal',
                'description' => 'Sesión de terapia de hasta 6 personas',
                'maxtime' => 60,
                'allowed_modes' => 2,
                'nomenclature_id' => 3,
                'coverage_id' => 1
            ],
            [
                'name' => 'Psicoterapia de Pareja o Familia',
                'description' => 'Sesión de terapia de 2 a 6 personas',
                'maxtime' => 60,
                'allowed_modes' => 2,
                'nomenclature_id' => 3,
                'coverage_id' => 2
            ],
            [
                'name' => 'Pruebas Psicométricas',
                'description' => 'Evaluación de conocimientos, aptitudes psíquicas, comportamientos, rasgos de personalidad y capacidades del individuo',
                'maxtime' => 40,
                'allowed_modes' => 2,
                'nomenclature_id' => 4,
                'coverage_id' => 1
            ],
            [
                'name' => 'Psicodiagnóstico',
                'description' => 'Diagnóstico de enfermedades, síndromes o alteraciones mentales',
                'maxtime' => 60,
                'allowed_modes' => 2,
                'nomenclature_id' => 4,
                'coverage_id' => 2
            ],
            
        ];

        $prices = [
            [
                'price' => 602.19,
                'copayment' => 0.00,
                'practice_id' => 1,
                'coverage_id' => 2
            ],
            [
                'price' => 567.31,
                'copayment' => 0.00,
                'practice_id' => 2,
                'coverage_id' => 2
            ],
            [
                'price' => 550.48,
                'copayment' => 0.00,
                'practice_id' => 3,
                'coverage_id' => 2
            ],
            [
                'price' => 550.48,
                'copayment' => 0.00,
                'practice_id' => 4,
                'coverage_id' => 2
            ],
            [
                'price' => 550.48,
                'copayment' => 0.00,
                'practice_id' => 5,
                'coverage_id' => 2
            ],
            [
                'price' => 550.48,
                'copayment' => 0.00,
                'practice_id' => 6,
                'coverage_id' => 2
            ]
        ];

        $specialties = [
            [
                'name' => 'psicology',
                'displayname' => 'Psicología'
            ],
            [
                'name' => 'odontology',
                'displayname' => 'Odontología'
            ],
            [
                'name' => 'general-medicine',
                'displayname' => 'Medicina general'
            ],
            [
                'name' => 'phonoaudiology',
                'displayname' => 'Fonoaudiología'
            ],
            [
                'name' => 'neurology',
                'displayname' => 'Neurología'
            ]
        ];

        foreach ($coverages as $key => $value) {
            $coverage = Coverage::create([
                'name' => $value['name'],
                'address' => $value['address'],
                'phone' => $value['phone'],
                'city_id' => $value['city_id']
            ]);
        }

        foreach ($specialties as $key => $value) {
            $specialty = Specialty::create([
                'name' => $value['name'],
                'displayname' => $value['displayname']
            ]);
        }

        foreach ($nomenclatures as $key => $value) {
            $nomenclature = Nomenclature::create([
                'code' => $value['code'],
                'description' => $value['description'],
                'specialty_id' => 1
            ]);
        }

        foreach ($practices as $key => $value) {
            $practice = Practice::create([
                'name' => $value['name'],
                'description' => $value['description'],
                'maxtime' => $value['maxtime'],
                'allowed_modes' => $value['allowed_modes'],
                'nomenclature_id' => $value['nomenclature_id'],
                'coverage_id' => $value['coverage_id']
            ]);
        }

        foreach ($prices as $key => $value) {
            $price = Price::create([
                'price' => $value['price'],
                'copayment' => $value['copayment'],
                'practice_id' => $value['practice_id'],
                'coverage_id' => $value['coverage_id']
            ]);
        }
    }
}