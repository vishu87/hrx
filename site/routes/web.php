<?php

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

use App\Scheme;

Route::get('/', 'FrontController@home');
Route::get('/about-us/{id}/abcd/{var2}', 'FrontController@aboutUs');

Route::get('/login', 'UserController@login')->name("login");
Route::post('/login', 'UserController@postLogin');

Route::get('/forget-password', 'UserController@forgetPassword');
Route::post('/forget-password', 'UserController@postForgetPassword');

Route::get('/logout',function(){
	Auth::logout();
	return Redirect::to('/');
});

Route::group(["middleware"=>["auth"]],function(){

	Route::get('/admin/dashboard', 'AdminController@dashboard');
	Route::get('/admin/customers', 'AdminController@customers');

});

