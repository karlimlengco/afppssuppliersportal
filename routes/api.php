<?php

use Illuminate\Http\Request;

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

Route::group(['middleware' => ['api']], function () {
    Route::post('/signin', [
        'uses' => 'AuthController@signin',
    ]);

    Route::group(['middleware' => 'jwt.auth'], function () {

        Route::get('/user-lists', [
            'uses' => 'UserController@listAll',
        ]);

        Route::get('/user', [
            'uses' => 'UserController@index',
        ]);

        Route::post('/updateUser', [
            'uses' => 'UserController@store',
        ]);

        Route::resource('catered-units', 'Maintenance\CateredUnitController');
        Route::resource('permissions', 'Maintenance\PermissionController');
        Route::resource('roles', 'Maintenance\RoleController');
        Route::resource('account-codes', 'Maintenance\AccountController');
        Route::resource('unit-purchase-requests', 'Procurement\UPRController');
        Route::resource('signatories', 'Maintenance\SignatoryController');
        Route::resource('upr-items', 'Procurement\UPRItemController');
        Route::resource('procurement-centers', 'Maintenance\PCCOController');
        Route::resource('procurement-types', 'Maintenance\TypesController');
        Route::resource('mode-of-procurements', 'Maintenance\ModesController');
        Route::resource('chargeability', 'Maintenance\ChargeabilityController');
        Route::resource('payment-terms', 'Maintenance\TermsController');
    });

});