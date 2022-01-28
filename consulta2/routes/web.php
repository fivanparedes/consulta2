<?php

use App\Http\Controllers\CiteController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\MedicalHistoryController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProfessionalController;
use App\Http\Controllers\ReminderController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/clearapp', function() {
	Artisan::call('config:clear');
	Artisan::call('route:clear');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Session::flush();
    return redirect('/');
});




Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('dashboard');

Route::group(['middleware' => 'auth'], function () {

	/**
	 * Admin & Institution common routes
	 */
	Route::get('/manage/professionals', ['as' => 'admin.professionals', 'uses' => 'App\Http\Controllers\ProfessionalController@index']);
	Route::get('/manage/professionals/pdf', ['as' => 'admin.professionals.pdf', 'uses' => 'App\Http\Controllers\ProfessionalController@createPDF']);
	Route::get('/manage/professionals/create', ['as' => 'admin.professionals.create', 'uses' => 'App\Http\Controllers\ProfessionalController@create']);
	Route::post('/manage/professionals/store/', ['as' => 'admin.professionals.store', 'uses' => 'App\Http\Controllers\ProfessionalController@store']);
	Route::get('/manage/professionals/edit/{id}', ['as' => 'admin.professionals.edit', 'uses' => 'App\Http\Controllers\ProfessionalController@edit']);
	Route::put('/manage/professionals/update/{id}', ['as' => 'admin.professionals.update', 'uses' => 'App\Http\Controllers\ProfessionalController@save']);
	Route::get('/manage/patients/edit/{id}', ['as' => 'admin.patients.edit', 'uses' => 'App\Http\Controllers\PatientController@edit']);
	Route::resource('/manage/patients', 'App\Http\Controllers\PatientController', ['except' => 'show']);
	Route::get('/manage/patients/pdf', ['as' => 'admin.patients.pdf', 'uses' => 'App\Http\Controllers\PatientController@createPDF'] );
	/**
	 * Controlador de eventos (turnos de calendario)
	 */
	Route::get('/show-event-calendar', [EventController::class, 'index']);
	Route::post('/manage-events', [EventController::class, 'manageEvents']);
	Route::get('/show-available-hours', [EventController::class, 'showAvailableTimes']);
	Route::get('/event/confirm', [EventController::class, 'confirm']);
	Route::post('/event/store', [EventController::class, 'store']);
	Route::post('/event/massCancel', [EventController::class, 'massCancel']);

	Route::get('/external/event/cancel/{id}', [EventController::class, 'externalCancel']);
	Route::delete('/external/event/delete/{id}', [EventController::class, 'externalDelete']);
	Route::get('/reminder/confirm/{id}', [ReminderController::class, 'confirm']);

	Route::resource('/consult_types', 'App\Http\Controllers\ConsultTypeController');
	Route::get('/getAvailableHours', 'App\Http\Controllers\ConsultTypeController@getCategorizedHours');
	Route::resource('/practices', 'App\Http\Controllers\PracticeController');
	Route::resource('/nomenclatures', 'App\Http\Controllers\NomenclatureController');
	Route::resource('/coverages', 'App\Http\Controllers\CoverageController', ['except' => 'createPDF']);
	Route::get('/coverages/pdf', 'App\Http\Controllers\CoverageController@createPDF');
	Route::resource('/non_workable_days', 'App\Http\Controllers\NonWorkableDayController');
	Route::resource('/institutions', 'App\Http\Controllers\InstitutionController');
	Route::get('/institutions/pdf', 'App\Http\Controllers\InstitutionController@createPDF');

	/**
	 * Controlador de citas (sesiones de consultorio)
	 */
	Route::get('cite', ['as' => 'cite.index', 'uses' => 'App\Http\Controllers\CiteController@index']);
	//Route::get('/cite/{id}', ['as' => 'cite.show', 'uses' => 'App\Http\Controllers\CiteController@show']);
	Route::get('/cite/edit/{id}', ['as' => 'cite.edit', 'uses' => 'App\Http\Controllers\CiteController@edit']);
	Route::get('/cite/pdf', ['as' => 'cite.pdf', 'uses' => 'App\Http\Controllers\CiteController@createPDF']);
	Route::patch('/cite/update/{id}', ['as' => 'cite.update', 'uses' => 'App\Http\Controllers\CiteController@update']);
	
	
	/**
	 * Perfiles de usuario
	 */
	Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
	Route::patch('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
	Route::get('profile/info', ['as' => 'profile.infoedit', 'uses' => 'App\Http\Controllers\ProfileController@info']);
	Route::get('/profile/create', [PatientController::class, 'create']);
	Route::patch('profile/info/update', ['as' => 'generalinfo.update', 'uses' => 'App\Http\Controllers\PatientController@update']);
	Route::patch('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);
	
	/**
	 * Perfiles de pacientes (para ojos de profesionales)
	 */
	Route::get('/profile/lifesheet', ['as' => 'profile.lifesheet', 'uses' => 'App\Http\Controllers\LifesheetController@show']);
	Route::patch('lifesheet/update', ['as' => 'lifesheet.update', 'uses' => 'App\Http\Controllers\LifesheetController@update']);
	Route::get('/profile/events', ['as' => 'profile.events', 'uses' => 'App\Http\Controllers\PatientController@listEvents']);
	Route::get('/profile/events/{id}', [PatientController::class, 'showEvent']);
	Route::delete('/profile/events/delete/{id}', ['as' => 'profile.events.delete', 'uses' => 'App\Http\Controllers\EventController@delete']);
	Route::get('/profile/attendees', ['as' => 'profile.attendees', 'uses' => 'App\Http\Controllers\UserController@listAtendees']);
	Route::get('/users/searchByDni', 'App\Http\Controllers\UserController@searchByDni');
	
	/**
	 * Historias médicas (ultra sensible)
	 */
	//Route::get('/profile/attendees/history/{id}', ['as' => 'history.show', 'uses' => 'App\Http\Controllers\MedicalHistoryController@show']);
	Route::get('/profile/attendees/history/decryptContent', [MedicalHistoryController::class, 'decryptField']);
	Route::patch('/profile/attendees/history/update/{id}', ['as' => 'history.update', 'uses' => 'App\Http\Controllers\MedicalHistoryController@update']);
	Route::get('/medical_history', 'App\Http\Controllers\MedicalHistoryController@index');
	
	Route::get('/medical_history/create', 'App\Http\Controllers\MedicalHistoryController@create');
	Route::post('/medical_history', 'App\Http\Controllers\MedicalHistoryController@store');
	Route::get('/medical_history/{id}/edit', 'App\Http\Controllers\MedicalHistoryController@edit');
	Route::patch('/medical_history', 'App\Http\Controllers\MedicalHistoryController@index');
	Route::delete('/medical_history/{id}', 'App\Http\Controllers\MedicalHistoryController@destroy');
	Route::get('/medical_history/toggleInstitutionPrivilege', 'App\Http\Controllers\MedicalHistoryController@toggleInstitutionPrivilege');
	Route::get('/medical_history/locatePatient', 'App\Http\Controllers\MedicalHistoryController@locatePatient');
	Route::get('/medical_history/{id}', 'App\Http\Controllers\MedicalHistoryController@show');
	Route::get('/medical_history/{id}/pdf', 'App\Http\Controllers\MedicalHistoryController@createPDF');
	/**
	 * Perfiles profesionales (publicos)
	 */
	Route::get('professionals/list/', ['as' => 'professional.index', 'uses' => 'App\Http\Controllers\ProfessionalController@index']);
	Route::get('professionals/getFilteredList', [ProfessionalController::class, 'getFilteredProfessionals']);
	Route::get('professional/show/{id}', ['as' => 'professional.show', 'uses' => 'App\Http\Controllers\ProfessionalController@show']);

	Route::get('institutions/list', ['as' => 'institution.list', 'uses' => 'App\Http\Controllers\InstitutionController@list']);
	Route::get('institutions/getFilteredList', [InstitutionController::class, 'getFilteredInstitutions']);
	Route::get('institution/{id}/getFilteredList', [InstitutionController::class, 'getFilteredList']);

});