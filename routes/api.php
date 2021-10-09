<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([ 'prefix' => 'driver', 'namespace' => 'Driver'], static function ($router) {
    Route::post('login', 'DriverController@login');
    Route::post('signup', 'DriverController@register');
    Route::post('logout', 'DriverController@logout');
    Route::post('profile', 'ProfileController@profile');
    Route::post('update-profile', 'ProfileController@update_profile');
    Route::post('cars', 'ProfileController@cars');
    Route::post('change-password', 'ProfileController@change_password');
    Route::post('change-pic', 'ProfileController@change_pic');
    Route::post('my-services', 'ServiceController@my_services');
    Route::post('ok-service', 'ServiceController@ok_service');
    Route::post('new-position', 'ServiceController@new_position');
    Route::post('busy-position', 'ServiceController@busy_position');
    Route::post('new-car', 'ServiceController@new_car');
    Route::post('delete-car', 'ServiceController@delete_car');
    Route::group(['middleware' => 'driver.verify'], static function( $router){
        Route::post('refresh', 'DriverController@refresh');
        Route::get('detail', 'DriverController@detail'); });
});

Route::group([ 'namespace' => 'Client'], static function ($router) {
    Route::post('login', 'ClientController@login');
    Route::post('signup', 'ClientController@register');
    Route::post('logout', 'ClientController@logout');
    Route::post('profile', 'ProfileController@profile');
    Route::post('update-profile', 'ProfileController@update_profile');
    Route::post('change-password', 'ProfileController@change_password');
    Route::post('change-pic', 'ProfileController@change_pic');
    Route::post('profile/favorites', 'FavoriteController@favorites');
    Route::post('profile/new-favorite', 'FavoriteController@new_favorites');
    Route::post('profile/delete-favorite', 'FavoriteController@delete_favorite');
    Route::post('profile/update-favorite', 'FavoriteController@update_favorite');
    Route::post('near-taxi', 'ServiceController@near_taxi');
    Route::post('service-notification', 'ServiceController@service_notification');
    Route::post('service-qualification', 'ServiceController@service_qualification');
    Route::group(['middleware' => 'client.verify'], static function( $router){
        Route::post('refresh', 'ClientController@refresh');
        Route::get('detail', 'ClientController@detail'); });
});
Route::get('map/info', 'MapController@index');

