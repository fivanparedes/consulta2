<?php

use App\Http\Controllers\CiteController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\MedicalHistoryController;
use App\Http\Controllers\PatientController;
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
	 * Admin routes
	 */
	Route::get('/admin/professionals', ['as' => 'admin.professionals', 'uses' => 'App\Http\Controllers\ProfessionalController@adminList']);
	Route::get('/admin/professionals/edit/{id}', ['as' => 'admin.professionals.edit', 'uses' => 'App\Http\Controllers\ProfessionalController@edit']);
	Route::put('/admin/professionals/store/{id}', ['as' => 'admin.professionals.store', 'uses' => 'App\Http\Controllers\ProfessionalController@save']);
	/**
	 * Controlador de eventos (turnos de calendario)
	 */
	Route::get('/show-event-calendar', [EventController::class, 'index']);
	Route::post('/manage-events', [EventController::class, 'manageEvents']);
	Route::get('/show-available-hours', [EventController::class, 'showAvailableTimes']);
	Route::get('/event/confirm', [EventController::class, 'confirm']);
	Route::post('/event/store', [EventController::class, 'store']);

	/**
	 * Controlador de citas (sesiones de consultorio)
	 */
	Route::get('cite', ['as' => 'cite.index', 'uses' => 'App\Http\Controllers\CiteController@index']);
	Route::get('/cite/{id}', ['as' => 'cite.show', 'uses' => 'App\Http\Controllers\CiteController@show']);
	Route::get('/cite/edit/{id}', ['as' => 'cite.edit', 'uses' => 'App\Http\Controllers\CiteController@edit']);
	Route::get('/cite/pdf/{filter1}/{filter2}', [CiteController::class, 'createPDF']);
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
	Route::get('/profile/attendees', ['as' => 'profile.attendees', 'uses' => 'App\Http\Controllers\UserController@listAtendees']);
	
	/**
	 * Historias médicas (ultra sensible)
	 */
	Route::get('/profile/attendees/history/{id}', ['as' => 'history.show', 'uses' => 'App\Http\Controllers\MedicalHistoryController@show']);
	Route::get('/profile/attendees/history/decryptContent', [MedicalHistoryController::class, 'decryptField']);
	Route::patch('/profile/attendees/history/update/{id}', ['as' => 'history.update', 'uses' => 'App\Http\Controllers\MedicalHistoryController@update']);
	/**
	 * Perfiles profesionales (publicos)
	 */
	Route::get('professionals/list/', ['as' => 'professional.index', 'uses' => 'App\Http\Controllers\ProfessionalController@index']);
	Route::get('professional/show/{id}', ['as' => 'professional.show', 'uses' => 'App\Http\Controllers\ProfessionalController@show']);
});