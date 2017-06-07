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
| Settings Routes
|--------------------------------------------------------------------------
|
*/
Route::group(['as' => 'settings.', 'prefix' => 'settings'], function () {

    /*
    |--------------------------------------------------------------------------
    | Admin Routes
    |--------------------------------------------------------------------------
    |
    */
    Route::resource('users', '\Revlv\Controllers\Sentinel\UserController');
    Route::resource('roles', '\Revlv\Controllers\Sentinel\RoleController');
    Route::resource('permissions', '\Revlv\Controllers\Sentinel\PermissionController');
    Route::resource('user/groups', '\Revlv\Controllers\Sentinel\UserGroupController');

    /*
    |--------------------------------------------------------------------------
    | Audit Logs Routes
    |--------------------------------------------------------------------------
    |
    */
    Route::resource('audit-logs', '\Revlv\Controllers\Settings\AuditLogController');
});



/*
|--------------------------------------------------------------------------
| Datatables Routes
|--------------------------------------------------------------------------
|
*/
Route::group(['as' => 'datatables.', 'prefix' => 'datatables'], function () {

    Route::get('audit-logs', '\Revlv\Controllers\Settings\AuditLogController@getDatatable')->name('audit-logs');


    /*
    |--------------------------------------------------------------------------
    | Admin Routes
    |--------------------------------------------------------------------------
    |
    */
    Route::get('permissions', '\Revlv\Controllers\Sentinel\PermissionController@getDatatable')->name('permissions');
    Route::get('roles', '\Revlv\Controllers\Sentinel\RoleController@getDatatable')->name('roles');
    Route::get('users', '\Revlv\Controllers\Sentinel\UserController@getDatatable')->name('users');
});




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
Route::get('register', '\Revlv\Controllers\AuthController@create')->name('auth.create');
Route::post('register', '\Revlv\Controllers\AuthController@register')->name('auth.register');

/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
| All user Routes
|
|
*/
Route::get('user/{id}/avatar', '\Revlv\Controllers\Sentinel\UserController@showAvatar')->name('user.avatar.show');
Route::get('users/admin-reset', '\Revlv\Controllers\Sentinel\ReminderController@adminremind')->name('reminder.reset');

Route::get('users/reset', '\Revlv\Controllers\Sentinel\ReminderController@index')->name('reminder.index');
Route::post('users/reset', '\Revlv\Controllers\Sentinel\ReminderController@remind')->name('reminder.submit');

Route::get('users/password', '\Revlv\Controllers\Sentinel\ResetPasswordController@index')->name('password.index');
Route::post('users/password', '\Revlv\Controllers\Sentinel\ResetPasswordController@change')->name('password.change');

Route::get('users/activate', '\Revlv\Controllers\ActivationController@index')->name('activation.index');
Route::post('users/activate', '\Revlv\Controllers\ActivationController@activate')->name('activation.activate');

Route::any('profile/update/{id}', '\Revlv\Controllers\Sentinel\UserController@updateProfile')->name('profile.update');
Route::get('profile', '\Revlv\Controllers\Sentinel\UserController@showProfile')->name('profile.show');

Route::group(['as' => 'user.', 'prefix' => 'user'], function () {
    Route::resource('groups', '\Revlv\Controllers\Sentinel\UserGroupController');
});