<?php

use App\Http\Controllers\CiteController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PatientController;
use Illuminate\Support\Facades\Auth;

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

Route::get('/show-event-calendar', [EventController::class, 'index']);
Route::post('/manage-events', [EventController::class, 'manageEvents']);


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('dashboard');

Route::group(['middleware' => 'auth'], function () {
	Route::get('cite', ['as' => 'cite.index', 'uses' => 'App\Http\Controllers\CiteController@index']);
	Route::get('/cite/{id}', ['as' => 'cite.show', 'uses' => 'App\Http\Controllers\CiteController@show']);
	Route::get('/cite/edit/{id}', ['as' => 'cite.edit', 'uses' => 'App\Http\Controllers\CiteController@edit']);
	Route::get('/cite/pdf/{filter1}/{filter2}', [CiteController::class, 'createPDF']);
	Route::patch('/cite/update/{id}', ['as' => 'cite.update', 'uses' => 'App\Http\Controllers\CiteController@update']);
	Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
	Route::patch('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
	Route::get('profile/info', ['as' => 'profile.infoedit', 'uses' => 'App\Http\Controllers\ProfileController@info']);
	Route::get('/profile/create', [PatientController::class, 'create']);
	Route::patch('profile/info/update', ['as' => 'generalinfo.update', 'uses' => 'App\Http\Controllers\PatientController@update']);
	Route::patch('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);
	Route::get('professionals/list/', ['as' => 'professional.index', 'uses' => 'App\Http\Controllers\ProfessionalController@index']);
});

Route::group(['middleware' => 'auth'], function () {
	Route::get('{page}', ['as' => 'page.index', 'uses' => 'App\Http\Controllers\PageController@index']);
});

