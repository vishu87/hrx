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

Route::get('/', 'UserController@login')->name("login");
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

Route::group(["middleware"=>["auth","ses"],"prefix"=>"admin"],function(){

	Route::get('/dashboard','AdminController@dashboard');
	
	Route::group(["prefix"=>"companies"],function(){
		Route::get('/','CompanyController@companies');
		Route::get('/add','CompanyController@addcompany');
		Route::get('/view/{company_id}','CompanyController@companyview');
		
	});

	Route::group(["prefix"=>"users"],function(){
		Route::get('/','AdminUserController@index');
		Route::get('/add/{id?}','AdminUserController@addusers');
		Route::post('/store/{id?}','AdminUserController@store');
		Route::get('/delete/{id?}','AdminUserController@deleteUser');
		Route::get('/active/{id?}','AdminUserController@activeUser');
	});
	
});

Route::group(["middleware"=>["auth","ses"],"prefix"=>"analytics"],function(){
	
	Route::get('/job-offers','AnalyticsController@jobOffers');
	Route::get('/candidates', 'AnalyticsController@candidates');
	Route::get('/activities', 'AnalyticsController@activities');

});

Route::group(["middleware"=>["auth","ses"],"prefix"=>"api/analytics"],function(){
	Route::get('/job-offers/params','AnalyticsController@jobOffersParams');
	Route::post('/job-offers/list','AnalyticsController@jobOffersList');

	Route::get('/candidates/params', 'AnalyticsController@candidateParams');
	Route::post('/candidates/list', 'AnalyticsController@candidateList');

	Route::group(["prefix"=>"activities"],function(){
		Route::get('/params', 'AnalyticsController@activityParams');
		Route::post('/list', 'AnalyticsController@activityList');
	});
});

// Route::group(["middleware"=>["auth","admin"],"prefix"=>"company"],function(){
	
// 	Route::get('/dashboard','CompanyController@dashboard');

// 	Route::group(["prefix"=>"users"],function(){
// 		Route::get('/','UserAdminController@index');
// 		Route::get('/add/{id?}','UserAdminController@add');
// 		Route::post('/store/{id?}','UserAdminController@store');
// 		// Route::get('/delete/{user_id?}','UserAdminController@delete');
// 	});

// 	Route::group(["prefix"=>"job-offers"],function(){
// 		Route::get('/','JobOffersController@index');	
// 		Route::get('/add/{user_id?}','JobOffersController@addOfferLetter');
// 		Route::get('/delete/{id}','JobOffersController@deleteOffer');

// 	});

// });

Route::group(["middleware"=>["auth"],"prefix"=>"api" ],function(){
	// Route::get('get-report/{report_id}','SESController@getReport');

	Route::group(["prefix"=>"company"],function(){
		Route::post('/save','CompanyController@storeCompany');

		Route::group(["prefix"=>"job-offers"],function(){
			Route::post('/init','JobOffersController@offersInit');
			Route::post('/save','JobOffersController@store');
		});
		Route::get('/users','UserAdminController@userInit');
		Route::get('/delete/{id}','UserAdminController@deleteUser');

	});

	Route::group(["prefix"=>"admin"],function(){
		
		Route::group(["prefix"=>"companies"],function(){
			Route::post('/','CompanyController@listing');
			Route::post('/init','CompanyController@companiesInit');
			Route::post('/save','CompanyController@storeCompany');
			Route::get('/delete/{company_id}','CompanyController@deleteCompany');
			Route::post('/store/{company_id}','CompanyController@storeUser');

		});

		Route::post('updateCompany','CompanyController@updateCompanies');
		Route::get('/users','AdminController@userInit');
		Route::get('/delete/{id}','AdminController@deleteUser');

	});

});