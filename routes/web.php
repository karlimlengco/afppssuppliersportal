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
Route::get('/', '\Revlv\Controllers\DashboardController@index')->name('dashboard.index');


/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
| login/logout Routes
|
|
*/
Route::get('auth/login', '\Revlv\Controllers\AuthController@index')->name('auth.index');
Route::get('auth/logout', '\Revlv\Controllers\AuthController@logout')->name('auth.logout');
Route::post('auth/login', '\Revlv\Controllers\AuthController@login')->name('auth.login');

Route::get('procurements/ongoing', '\Revlv\Controllers\ProcurementController@ongoing')->name('procurements.ongoing');
Route::get('procurements/awarded', '\Revlv\Controllers\ProcurementController@awarded')->name('procurements.awarded');
Route::get('procurements/failed', '\Revlv\Controllers\ProcurementController@failed')->name('procurements.failed');
Route::get('procurements/paid', '\Revlv\Controllers\ProcurementController@paid')->name('procurements.paid');
Route::get('procurements/all', '\Revlv\Controllers\ProcurementController@all')->name('procurements.all');

Route::resource('eligibilities', '\Revlv\Controllers\EligibilityController');

/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
| All user Routes
|
|
*/
Route::get('user/{id}/avatar', '\Revlv\Controllers\Sentinel\UserController@showAvatar')->name('user.avatar.show');

Route::get('users/password', '\Revlv\Controllers\Sentinel\ResetPasswordController@index')->name('password.index');
Route::post('users/password', '\Revlv\Controllers\Sentinel\ResetPasswordController@change')->name('password.change');

Route::any('profile/update/{id}', '\Revlv\Controllers\Sentinel\UserController@updateProfile')->name('profile.update');
Route::get('profile', '\Revlv\Controllers\Sentinel\UserController@showProfile')->name('profile.show');

Route::group(['as' => 'user.', 'prefix' => 'user'], function () {
    Route::resource('groups', '\Revlv\Controllers\Sentinel\UserGroupController');
});
Route::group(['as' => 'settings.', 'prefix' => 'settings'], function () {

    Route::resource('users', '\Revlv\Controllers\Sentinel\UserController');
});