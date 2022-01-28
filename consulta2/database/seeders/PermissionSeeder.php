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

            /**
             * Profile perms
             * No se si dejar las descripciones en español o ingles o veo como miercoles hago para implementar traduccion en un futuro...
             */
            [
                'name' => 'patient-profile',
                'display_name' => 'Patient Profile',
                'description' => 'These users can register and manage their own calendar'
            ],
            [
                'name' => 'professional-profile',
                'display_name' => 'Professional Profile',
                'description' => 'These users can register, manage calendar and see clinical histories'
            ],
            [
                'name' => 'institution-profile',
                'display_name' => 'Institution Profile',
                'description' => "It's a special kind of profile that represents an organisation."
            ],
            [
                'name' => 'admin-profile',
                'display_name' => 'Admin profile',
                'description' => "Es un usuario administrativo, no puede recibir turnos ni ver ."
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
                'name' => 'CiteController@index',
                'display_name' => 'Consultas: ver',
                'description' => 'Ver lista de consultas, mas no modificarlas'
            ],
            [
                'name' => 'CiteController@show',
                'display_name' => 'Consultas: crear',
                'description' => 'Agendar turnos desde el lado institucional/profesional'
            ],
            [
                'name' => 'CiteController@edit',
                'display_name' => 'Consultas: modificar',
                'description' => 'Modificar información de consultas/sesiones ya creadas'
            ],
            [
                'name' => 'CiteController@destroy',
                'display_name' => 'Consultas: borrar',
                'description' => 'Elimina toda la información relacionada a una sesión (observaciones, datos de la sesión, datos del turno agendado, etc)'
            ],
            [
                'name' => 'manage-histories',
                'display_name' => 'Administrar historias médicas',
                'description' => 'Puede ver, crear y eliminar historias médicas.'
            ],
            [
                'name' => 'EventController@massCancel',
                'display_name' => 'Turnos: días no laborables',
                'description' => 'Fijar días/rangos de días no laborables y cancelar a la vez todo turno agendado en esos días.'
            ],
            [
                'name' => 'EventController@delete',
                'display_name' => 'Turnos: cancelar',
                'description' => 'Cancelar turnos individualmente.'
            ],
            [
                'name' => 'EventController@store',
                'display_name' => 'Turnos: agendar',
                'description' => 'Agendar turnos desde cualquier clase de perfil (Paciente/Profesional/Institucional/Admin).'
            ],
            [
                'name' => 'ConsultTypeController@index',
                'display_name' => 'Horarios de atención: ver',
                'description' => 'Ver lista de horarios de atención, mas no modificarlos.'
            ],
            [
                'name' => 'ConsultTypeController@create',
                'display_name' => 'Horarios de atención: crear',
                'description' => 'Crear horarios de atención'
            ],
            [
                'name' => 'ConsultTypeController@edit',
                'display_name' => 'Horarios de atención: crear',
                'description' => 'Editar horarios de atención existentes'
            ],
            [
                'name' => 'ConsultTypeController@destroy',
                'display_name' => 'Horarios de atención: borrar',
                'description' => 'Elimina un horario de atención (y transporta sus turnos al primer registro)'
            ],
            [
                'name' => 'CoverageController@index',
                'display_name' => 'Obras sociales: ver',
                'description' => 'Ver lista de obras sociales'
            ],
            [
                'name' => 'CoverageController@create',
                'display_name' => 'Obras sociales: crear',
                'description' => 'Crear obra social.'
            ],
            [
                'name' => 'CoverageController@edit',
                'display_name' => 'Obras sociales: modificar',
                'description' => 'Modificar información de obras socailes ya creadas'
            ],
            [
                'name' => 'CoverageController@destroy',
                'display_name' => 'Obras sociales: borrar',
                'description' => 'Elimina la obra social'
            ],
            [
                'name' => 'InstitutionController@index',
                'display_name' => 'Instituciones: ver',
                'description' => 'Ver lista de instituciones (lado administrativo)'
            ],
            [
                'name' => 'InstitutionController@create',
                'display_name' => 'Instituciones: crear',
                'description' => 'Crear una institución.'
            ],
            [
                'name' => 'InstitutionController@edit',
                'display_name' => 'Obras sociales: modificar',
                'description' => 'Modificar una institución existente.'
            ],
            [
                'name' => 'InstitutionController@destroy',
                'display_name' => 'Institución: borrar',
                'description' => 'Elimina una institución (y convierte a sus prestadores en independientes)'
            ],
            [
                'name' => 'LifesheetController@show',
                'display_name' => 'Hojas de vida: ver',
                'description' => 'Ver hoja de vida del paciente (individualmente)'
            ],
            [
                'name' => 'LifesheetController@update',
                'display_name' => 'Hojas de vida: modificar',
                'description' => 'Modificar información de hojas de vida del paciente.'
            ],
            [
                'name' => 'MedicalHistoryController@index',
                'display_name' => 'Historias clínicas: ver',
                'description' => 'Ver historias clínicas. Advertencia: sensible.'
            ],
            [
                'name' => 'MedicalHistoryController@create',
                'display_name' => 'Historias clínicas: crear',
                'description' => 'Crear historias clínicas. Advertencia: sensible.'
            ],
            [
                'name' => 'MedicalHistoryController@edit',
                'display_name' => 'Historias clínicas: modificar',
                'description' => 'Modificar historias clínicas. Advertencia: sensible.'
            ],
            [
                'name' => 'MedicalHistoryController@destroy',
                'display_name' => 'Historias clínicas: borrar',
                'description' => 'Elimina historia clínicas. Advertencia: sensible.'
            ],
            [
                'name' => 'NomenclatureController@index',
                'display_name' => 'Nomenclador: ver',
                'description' => 'Ver lista de nomenclaturas'
            ],
            [
                'name' => 'NomenclatureController@create',
                'display_name' => 'Nomenclador: crear',
                'description' => 'Crear una nomenclatura.'
            ],
            [
                'name' => 'NomenclatureController@edit',
                'display_name' => 'Nomenclador: modificar',
                'description' => 'Modificar una nomenclatura existente.'
            ],
            [
                'name' => 'NomenclatureController@destroy',
                'display_name' => 'Nomenclador: borrar',
                'description' => 'Elimina una nomenclatura (y convierte a sus prácticas en "Sin nomenclatura")'
            ],
            [
                'name' => 'NonWorkableDayController@index',
                'display_name' => 'Días no laborables: ver',
                'description' => 'Ver lista de días no laborables.'
            ],
            [
                'name' => 'NonWorkableDayController@create',
                'display_name' => 'Días no laborables: crear',
                'description' => 'Crea un registro de días no laborables (pero no cancela los turnos).'
            ],
            [
                'name' => 'NonWorkableDayController@edit',
                'display_name' => 'Días no laborables: modificar',
                'description' => 'Modificar un registro de días no laborables existente.'
            ],
            [
                'name' => 'NonWorkableDayController@destroy',
                'display_name' => 'Días no laborables: borrar',
                'description' => 'Elimina un registro de días no laborables (permitiendo agendar turnos esos días)'
            ],
            [
                'name' => 'PatientController@index',
                'display_name' => 'Pacientes: ver',
                'description' => 'Ver lista de pacientes (admin: todos, institución: propios)'
            ],
            [
                'name' => 'PatientController@create',
                'display_name' => 'Pacientes: crear',
                'description' => 'Crear un paciente y sus elementos correspondientes (perfil/hoja de vida).'
            ],
            [
                'name' => 'PatientController@edit',
                'display_name' => 'Pacientes: modificar',
                'description' => 'Modificar un perfil de paciente existente.'
            ],
            [
                'name' => 'PatientController@destroy',
                'display_name' => 'Pacientes: borrar',
                'description' => 'Elimina un paciente (y todos los elementos que el mismo arrastra)'
            ],
            [
                'name' => 'PracticeController@index',
                'display_name' => 'Prácticas: ver',
                'description' => 'Ver lista de prácticas profesionales'
            ],
            [
                'name' => 'PracticeController@create',
                'display_name' => 'Prácticas: crear',
                'description' => 'Crear una práctica profesional.'
            ],
            [
                'name' => 'PracticeController@edit',
                'display_name' => 'Prácticas: modificar',
                'description' => 'Modificar una práctica existente.'
            ],
            [
                'name' => 'PracticeController@destroy',
                'display_name' => 'Prácticas: borrar',
                'description' => 'Elimina una práctica (y transporta a sus turnos a otra práctica a elección)'
            ],
            [
                'name' => 'PriceController@edit',
                'display_name' => 'Precios: modificar',
                'description' => 'Modificar un precio existente (no su práctica).'
            ],
            [
                'name' => 'ProfessionalController@index',
                'display_name' => 'Profesionales: ver',
                'description' => 'Ver lista de profesionales (lado administrativo)'
            ],
            [
                'name' => 'ProfessionalController@create',
                'display_name' => 'Profesionales: crear',
                'description' => 'Crear un profesional (la institución lo asigna a su perfil propio, admin elige libremente).'
            ],
            [
                'name' => 'ProfessionalController@edit',
                'display_name' => 'Profesionales: modificar',
                'description' => 'Modificar un perfil profesional existente.'
            ],
            [
                'name' => 'ProfessionalController@destroy',
                'display_name' => 'Profesional: borrar',
                'description' => 'Elimina un perfil profesional y sus elementos relacionados (se recomienda inhabilitar en su lugar)'
            ],
            [
                'name' => 'ProfilelController@edit',
                'display_name' => 'Perfil personal: modificar',
                'description' => 'Modificar un perfil personal existente (suyo y apoderado) Admin > Institución > Prestador > Paciente.'
            ],
            [
                'name' => 'ReminderController@index',
                'display_name' => 'Recordatorios: ver',
                'description' => 'Ver lista de recordatorios.'
            ],
            [
                'name' => 'ReminderController@create',
                'display_name' => 'Recordatorios: crear',
                'description' => 'Crear un recordatorio (que es enviado al paciente directamente).'
            ],
            [
                'name' => 'ReminderController@destroy',
                'display_name' => 'Recordatorios: borrar',
                'description' => 'Elimina un recordatorio manualmente (se eliminan automáticamente de todos modos)'
            ],
            [
                'name' => 'TreatmentController@index',
                'display_name' => 'Tratamientos: ver',
                'description' => 'Ver lista de tratamientos'
            ],
            [
                'name' => 'TreatmentController@create',
                'display_name' => 'Tratamientos: crear',
                'description' => 'Crear un tratamiento.'
            ],
            [
                'name' => 'TreatmentController@edit',
                'display_name' => 'Tratamientos: modificar',
                'description' => 'Modificar un tratamiento existente.'
            ],
            [
                'name' => 'TreatmentController@destroy',
                'display_name' => 'Tratamiento: borrar',
                'description' => 'Inhabilita un tratamiento. '
            ],
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