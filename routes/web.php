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
| Procurements Routes
|--------------------------------------------------------------------------
|
*/
Route::get('procurements', '\Revlv\Controllers\DashboardController@procurements')->name('procurements.index');
Route::group(['as' => 'procurements.', 'prefix' => 'procurements'], function () {
    Route::get('rfq/get-info/{id}', '\Revlv\Controllers\Procurements\BlankRFQController@getInfo')->name('rfq.get-info');
    Route::resource('unit-purchase-requests', '\Revlv\Controllers\Procurements\UPRController');
    Route::resource('blank-rfq', '\Revlv\Controllers\Procurements\BlankRFQController');
    Route::resource('philgeps-posting', '\Revlv\Controllers\Procurements\PhilGepsPostingController');
    Route::resource('ispq', '\Revlv\Controllers\Procurements\ISPQController');
    Route::resource('rfq-proponents', '\Revlv\Controllers\Procurements\RFQProponentController');
    Route::resource('canvassing', '\Revlv\Controllers\Procurements\CanvassingController');
    Route::post('rfq-proponents/attachments/{id}', '\Revlv\Controllers\Procurements\RFQProponentController@uploadAttachment')->name('rfq-proponents.attachments.store');
    Route::get('rfq-proponents/download/{id}', '\Revlv\Controllers\Procurements\RFQProponentController@downloadAttachment')->name('rfq-proponents.attachments.download');
    Route::post('award-to/{canvas}/{proponent_id}', '\Revlv\Controllers\Procurements\NoticeOfAwardController@awardToProponent')->name('notice-of-awards.award-to');

    Route::resource('noa', '\Revlv\Controllers\Procurements\NoticeOfAwardController');
    Route::post('purchase-orders/mfo-approved/{id}', '\Revlv\Controllers\Procurements\PurchaseOrderController@mfoApproved')->name('purchase-orders.mfo-approved');
    Route::post('purchase-orders/pcco-approved/{id}', '\Revlv\Controllers\Procurements\PurchaseOrderController@pccoApproved')->name('purchase-orders.pcco-approved');
    Route::resource('purchase-orders', '\Revlv\Controllers\Procurements\PurchaseOrderController');
    Route::resource('ntp', '\Revlv\Controllers\Procurements\NoticeToProceedController');
    Route::post('delivery-orders/create-purchase/{id}', '\Revlv\Controllers\Procurements\DeliveryController@createFromPurchase')->name('delivery-orders.create-purchase');
    Route::get('delivery-orders/completed/{id}', '\Revlv\Controllers\Procurements\DeliveryController@completeOrder')->name('delivery-orders.completed');
    Route::resource('delivery-orders', '\Revlv\Controllers\Procurements\DeliveryController');

    Route::get('inspection-and-acceptance/accepted/{id}', '\Revlv\Controllers\Procurements\InspectionAndAcceptanceController@acceptOrder')->name('inspection-and-acceptance.accepted');
    Route::resource('inspection-and-acceptance', '\Revlv\Controllers\Procurements\InspectionAndAcceptanceController');
    Route::post('delivery-to-coa/proceed/{id}', '\Revlv\Controllers\Procurements\DeliveryToCoaController@proceedDelivery')->name('delivery-to-coa.proceed');
    Route::resource('delivery-to-coa', '\Revlv\Controllers\Procurements\DeliveryToCoaController');

    Route::post('delivered-inspections/add-issue/{id}', '\Revlv\Controllers\Procurements\DeliveredInspectionReportController@addIssue')->name('delivered-inspections.add-issue');
    Route::post('delivered-inspections/start-inspection/{id}', '\Revlv\Controllers\Procurements\DeliveredInspectionReportController@startInspection')->name('delivered-inspections.start-inspection');
    Route::post('delivered-inspections/close-inspection/{id}', '\Revlv\Controllers\Procurements\DeliveredInspectionReportController@closeInspection')->name('delivered-inspections.close-inspection');
    Route::resource('delivered-inspections', '\Revlv\Controllers\Procurements\DeliveredInspectionReportController');
});


/*
|--------------------------------------------------------------------------
| Maintenance Routes
|--------------------------------------------------------------------------
|
*/

Route::group(['as' => 'maintenance.', 'prefix' => 'maintenance'], function () {

    /*
    |--------------------------------------------------------------------------
    | Procurement Maintenance Routes
    |--------------------------------------------------------------------------
    |
    */
    Route::resource('mode-of-procurements', '\Revlv\Controllers\Settings\ModeOfProcurementsController');
    Route::resource('procurement-centers', '\Revlv\Controllers\Settings\ProcurementCenterController');
    Route::resource('account-codes', '\Revlv\Controllers\Settings\AccountCodeController');
    Route::resource('chargeability', '\Revlv\Controllers\Settings\ChargeabilityController');
    Route::resource('payment-terms', '\Revlv\Controllers\Settings\PaymentTermController');
    Route::resource('units', '\Revlv\Controllers\Settings\UnitController');
    Route::resource('banks', '\Revlv\Controllers\Settings\BankController');

});


/*
|--------------------------------------------------------------------------
| Settings Routes
|--------------------------------------------------------------------------
|
*/

Route::get('settings', '\Revlv\Controllers\DashboardController@settings')->name('settings.index');
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


    Route::resource('suppliers', '\Revlv\Controllers\Settings\SupplierController');
    Route::resource('signatories', '\Revlv\Controllers\Settings\SignatoryController');

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

    /*
    |--------------------------------------------------------------------------
    | Settings Routes
    |--------------------------------------------------------------------------
    |
    */
    Route::get('suppliers', '\Revlv\Controllers\Settings\SupplierController@getDatatable')->name('settings.suppliers');
    Route::get('signatories', '\Revlv\Controllers\Settings\SignatoryController@getDatatable')->name('settings.signatories');

    /*
    |--------------------------------------------------------------------------
    | Maintenance Routes
    |--------------------------------------------------------------------------
    |
    */
    Route::get('mode-of-procurements', '\Revlv\Controllers\Settings\ModeOfProcurementsController@getDatatable')->name('maintenance.mode-of-procurements');
    Route::get('procurement-centers', '\Revlv\Controllers\Settings\ProcurementCenterController@getDatatable')->name('maintenance.procurement-centers');
    Route::get('account-codes', '\Revlv\Controllers\Settings\AccountCodeController@getDatatable')->name('maintenance.account-codes');
    Route::get('chargeability', '\Revlv\Controllers\Settings\ChargeabilityController@getDatatable')->name('maintenance.chargeability');
    Route::get('payment-terms', '\Revlv\Controllers\Settings\PaymentTermController@getDatatable')->name('maintenance.payment-terms');
    Route::get('units', '\Revlv\Controllers\Settings\UnitController@getDatatable')->name('maintenance.units');
    Route::get('banks', '\Revlv\Controllers\Settings\BankController@getDatatable')->name('maintenance.banks');

    /*
    |--------------------------------------------------------------------------
    | Procurement Routes
    |--------------------------------------------------------------------------
    |
    */
    Route::get('procurements.unit-purchase-requests', '\Revlv\Controllers\Procurements\UPRController@getDatatable')->name('procurements.unit-purchase-request');
    Route::get('blank-rfq', '\Revlv\Controllers\Procurements\BlankRFQController@getDatatable')->name('procurements.blank-rfq');
    Route::get('philgeps-posting', '\Revlv\Controllers\Procurements\PhilGepsPostingController@getDatatable')->name('procurements.philgeps-posting');
    Route::get('ispq', '\Revlv\Controllers\Procurements\ISPQController@getDatatable')->name('procurements.ispq');
    Route::get('canvassing', '\Revlv\Controllers\Procurements\CanvassingController@getDatatable')->name('procurements.canvassing');
    Route::get('noa', '\Revlv\Controllers\Procurements\NoticeOfAwardController@getDatatable')->name('procurements.noa');
    Route::get('ntp', '\Revlv\Controllers\Procurements\NoticeToProceedController@getDatatable')->name('procurements.ntp');
    Route::get('purchase-orders', '\Revlv\Controllers\Procurements\PurchaseOrderController@getDatatable')->name('procurements.purchase-orders');
    Route::get('delivery-orders', '\Revlv\Controllers\Procurements\DeliveryController@getDatatable')->name('procurements.delivery-orders');
    Route::get('inspection-and-acceptance', '\Revlv\Controllers\Procurements\InspectionAndAcceptanceController@getDatatable')->name('procurements.inspection-and-acceptance');

    Route::get('delivery-to-coa', '\Revlv\Controllers\Procurements\DeliveryToCoaController@getDatatable')->name('procurements.delivery-to-coa');
    Route::get('delivered-inspections', '\Revlv\Controllers\Procurements\DeliveredInspectionReportController@getDatatable')->name('procurements.delivered-inspections');

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