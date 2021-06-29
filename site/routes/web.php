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
use Illuminate\Support\Facades\Route;
use App\Classes\Theme\Metronic;

Route::get('/login', 'UserController@login')->name("login");
Route::post('/login', 'UserController@postLogin');
Route::get('/forget-password', 'UserController@forgetPassword');
Route::post('/forget-password', 'UserController@postForgetPassword');

Route::get('/logout',function(){
	Auth::logout();
	return Redirect::to('/');
});

Route::group(["middleware"=>["auth"]],function(){

	Route::get('settings','UserController@profile');
	Route::post('update-password','UserController@updatePassword');

	Route::get('update-password','UserController@changePassword');
	Route::post('update-password-first','UserController@updatePasswordFirstTime');
	
});

Route::group(["middleware"=>["auth"],"prefix"=>"admin"],function(){
	
	Route::get('/dashboard','AdminController@dashboard');
	
	Route::group(["prefix"=>"companies"],function(){
		Route::get('/','CompaniesController@companies');
		Route::get('/add','CompaniesController@addcompany');
	});

	Route::group(["prefix"=>"users"],function(){
		Route::get('/','AdminController@index');
		Route::get('/add/{id?}','AdminController@addusers');
		Route::post('/store/{id?}','AdminController@store');
	});
	
});

Route::group(["middleware"=>["auth","admin"],"prefix"=>"company"],function(){
	
	Route::get('/dashboard','CompaniesController@dashboard');

	Route::group(["prefix"=>"users"],function(){
		Route::get('/','UserAdminController@index');
		Route::get('/add/{id?}','UserAdminController@add');
		Route::post('/store/{id?}','UserAdminController@store');
		// Route::get('/delete/{user_id?}','UserAdminController@delete');
	});

	Route::group(["prefix"=>"job-offers"],function(){
		Route::get('/','JobOffersController@index');	
		Route::get('/add/{user_id?}','JobOffersController@addOfferLetter');
		Route::get('/delete/{id}','JobOffersController@deleteOffer');

	});

});

Route::group(["middleware"=>["auth"],"prefix"=>"api" ],function(){
	Route::group(["prefix"=>"company"],function(){
		Route::post('/save','CompaniesController@storeCompany');

		Route::group(["prefix"=>"job-offers"],function(){
			Route::post('/init','JobOffersController@offersInit');
			Route::post('/save','JobOffersController@store');
		});
		Route::get('/users','UserAdminController@userInit');
		Route::get('/delete/{id}','UserAdminController@deleteUser');

	});

	Route::group(["prefix"=>"admin"],function(){
		
		Route::group(["prefix"=>"companies"],function(){
			Route::get('/','CompaniesController@listing');
			Route::post('/init','CompaniesController@companiesInit');
			Route::post('/save','CompaniesController@storeCompany');
			Route::get('/delete/{company_id}','CompaniesController@deleteCompany');
			Route::get('/view','CompaniesController@viewcompany');

		});

		Route::post('updateCompany','CompaniesController@updateCompanies');
		Route::get('/users','AdminController@userInit');
		Route::get('/delete/{id}','AdminController@deleteUser');

	});

});