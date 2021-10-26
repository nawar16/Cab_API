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

Route::macro('ProfileRoutes', function ($uri, $controller) {
    Route::post("{$uri}/login", "{$controller}@login");
    Route::post("{$uri}/signup", "{$controller}@register");
    Route::post("{$uri}/logout", "{$controller}@logout");
    Route::post("{$uri}/profile", "{$controller}@profile");
    Route::post("{$uri}/update-profile", "{$controller}@update_profile");
    Route::post("{$uri}/change-password", "{$controller}@change_password");
    Route::post("{$uri}/change-pic", "{$controller}@change_pic");
});

Route::group([ 'prefix' => 'driver', 'namespace' => 'Driver'], static function ($router) {
    Route::ProfileRoutes('driver', 'DriverController');
    Route::post('cars', 'DriverController@cars');
    Route::post('my-services', 'ServiceController@my_services');
    Route::post('ok-service', 'ServiceController@ok_service');
    Route::post('new-position', 'ServiceController@new_position');
    Route::post('busy-position', 'ServiceController@busy_position');
    Route::post('new-car', 'ServiceController@new_car');
    Route::post('delete-car', 'ServiceController@delete_car');
    Route::post('refresh', 'DriverController@refresh');
});

Route::group([ 'prefix' => 'client', 'namespace' => 'Client'], static function ($router) {
    Route::ProfileRoutes('', 'ClientController');
    Route::post('profile/favorites', 'FavoriteController@favorites');
    Route::post('profile/new-favorite', 'FavoriteController@new_favorites');
    Route::post('profile/delete-favorite', 'FavoriteController@delete_favorite');
    Route::post('profile/update-favorite', 'FavoriteController@update_favorite');
    Route::post('near-taxi', 'ServiceController@near_taxi');
    Route::post('service-notification', 'ServiceController@service_notification');
    Route::post('service-qualification', 'ServiceController@service_qualification');
});
Route::get('map/info', 'MapController@index');

