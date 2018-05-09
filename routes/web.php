<?php

use Contabilizate\Regimen;

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
    return view('auth.login');
});

Route::group(['middleware' => 'auth'], function() {
    Route::get('usuarios', function() {
	    return view('usuarios.index');
	})->name('usuarios.all');
	Route::resource('api/usuarios', 'UsersController');

	Route::get('contribuyentes', function() {
		$regimenes = Regimen::all();
	    return view('contribuyentes.index', compact('regimenes'));
	})->name('contribuyentes.all');

	Route::resource('api/contribuyentes', 'ContributorsController');

});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
