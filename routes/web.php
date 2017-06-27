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

    /*
    |--------------------------------------------------------------------------
    | UPR Routes
    |--------------------------------------------------------------------------
    |
    */

    Route::post('unit-purchase-requests/attachments/{id}', '\Revlv\Controllers\Procurements\UPRController@uploadAttachment')->name('unit-purchase-requests.attachments.store');
    Route::get('unit-purchase-requests/timelines/{id}', '\Revlv\Controllers\Procurements\UPRController@timelines')->name('unit-purchase-requests.timelines');
    Route::get('unit-purchase-requests/download/{id}', '\Revlv\Controllers\Procurements\UPRController@downloadAttachment')->name('unit-purchase-requests.attachments.download');
    Route::get('unit-purchase-requests/imports', '\Revlv\Controllers\Procurements\UPRController@uploadView')->name('unit-purchase-requests.imports');
    Route::post('unit-purchase-requests/import-file', '\Revlv\Controllers\Procurements\UPRController@uploadFile')->name('unit-purchase-requests.import-file');
    Route::resource('unit-purchase-requests', '\Revlv\Controllers\Procurements\UPRController');

    /*
    |--------------------------------------------------------------------------
    | RFQ Routes
    |--------------------------------------------------------------------------
    |
    */
    Route::get('blank-rfq/print/{id}', '\Revlv\Controllers\Procurements\BlankRFQController@viewPrint')->name('blank-rfq.print');
    Route::get('rfq/get-info/{id}', '\Revlv\Controllers\Procurements\BlankRFQController@getInfo')->name('rfq.get-info');
    Route::get('blank-rfq/closed/{id}', '\Revlv\Controllers\Procurements\BlankRFQController@closed')->name('blank-rfq.closed');
    Route::resource('blank-rfq', '\Revlv\Controllers\Procurements\BlankRFQController');

    /*
    |--------------------------------------------------------------------------
    | PhilGeps Routes
    |--------------------------------------------------------------------------
    |
    */
    Route::post('philgeps-posting/attachments/{id}', '\Revlv\Controllers\Procurements\PhilGepsPostingController@uploadAttachment')->name('philgeps-posting.attachments.store');
    Route::get('philgeps-posting/download/{id}', '\Revlv\Controllers\Procurements\PhilGepsPostingController@downloadAttachment')->name('philgeps-posting.attachments.download');
    Route::resource('philgeps-posting', '\Revlv\Controllers\Procurements\PhilGepsPostingController');

    /*
    |--------------------------------------------------------------------------
    | ISPQ Routes
    |--------------------------------------------------------------------------
    |
    */
    Route::get('ispq/print/{id}', '\Revlv\Controllers\Procurements\ISPQController@viewPrint')->name('ispq.print');
    Route::post('ispq/store-by-rfq/{id}', '\Revlv\Controllers\Procurements\ISPQController@createByRFQ')->name('ispq.store-by-rfq');
    Route::resource('ispq', '\Revlv\Controllers\Procurements\ISPQController');

    /*
    |--------------------------------------------------------------------------
    | Canvass Routes
    |--------------------------------------------------------------------------
    |
    */
    Route::get('canvassing/print/{id}', '\Revlv\Controllers\Procurements\CanvassingController@viewPrint')->name('canvassing.print');
    Route::post('canvassing/signatories/{id}', '\Revlv\Controllers\Procurements\CanvassingController@addSignatories')->name('canvassing.signatories');
    Route::get('canvassing/opening/{id}', '\Revlv\Controllers\Procurements\CanvassingController@openCanvass')->name('canvassing.opening');
    Route::resource('canvassing', '\Revlv\Controllers\Procurements\CanvassingController');

    /*
    |--------------------------------------------------------------------------
    | RFQ Proponents Routes
    |--------------------------------------------------------------------------
    |
    */
    Route::post('rfq-proponents/attachments/{id}', '\Revlv\Controllers\Procurements\RFQProponentController@uploadAttachment')->name('rfq-proponents.attachments.store');
    Route::get('rfq-proponents/download/{id}', '\Revlv\Controllers\Procurements\RFQProponentController@downloadAttachment')->name('rfq-proponents.attachments.download');
    Route::get('rfq-proponents/delete{id}', '\Revlv\Controllers\Procurements\RFQProponentController@delete')->name('rfq-proponents.delete');
    Route::resource('rfq-proponents', '\Revlv\Controllers\Procurements\RFQProponentController');

    Route::post('award-to/{canvas}/{proponent_id}', '\Revlv\Controllers\Procurements\NoticeOfAwardController@awardToProponent')->name('notice-of-awards.award-to');

    /*
    |--------------------------------------------------------------------------
    | NOA Routes
    |--------------------------------------------------------------------------
    |
    */
    Route::post('noa/accepted', '\Revlv\Controllers\Procurements\NoticeOfAwardController@acceptNOA')->name('noa.accepted');
    Route::put('noa/update-signatory/{id}', '\Revlv\Controllers\Procurements\NoticeOfAwardController@updateSignatory')->name('noa.update-signatory');
    Route::get('noa/print/{id}', '\Revlv\Controllers\Procurements\NoticeOfAwardController@viewPrint')->name('noa.print');
    Route::get('noa/download/{id}', '\Revlv\Controllers\Procurements\NoticeOfAwardController@downloadCopy')->name('noa.download');
    Route::resource('noa', '\Revlv\Controllers\Procurements\NoticeOfAwardController');

    /*
    |--------------------------------------------------------------------------
    | Purchase Routes
    |--------------------------------------------------------------------------
    |
    */
    Route::get('purchase-orders/print/{id}', '\Revlv\Controllers\Procurements\PurchaseOrderController@viewPrint')->name('purchase-orders.print');
    Route::get('purchase-orders/print-terms/{id}', '\Revlv\Controllers\Procurements\PurchaseOrderController@viewPrintTerms')->name('purchase-orders.print-terms');
    Route::get('purchase-orders/print-coa/{id}', '\Revlv\Controllers\Procurements\PurchaseOrderController@viewPrintCOA')->name('purchase-orders.print-coa');
    Route::get('purchase-orders/rfq/{rfq_id}', '\Revlv\Controllers\Procurements\PurchaseOrderController@createFromRfq')->name('purchase-orders.rfq');
    Route::get('purchase-orders/coa-file/{id}', '\Revlv\Controllers\Procurements\PurchaseOrderController@downloadCoa')->name('purchase-orders.coa-file');
    Route::post('purchase-orders/coa-approved/{id}', '\Revlv\Controllers\Procurements\PurchaseOrderController@coaApproved')->name('purchase-orders.coa-approved');
    Route::post('purchase-orders/store-from-rfq/{rfq_id}', '\Revlv\Controllers\Procurements\PurchaseOrderController@storeFromRfq')->name('purchase-orders.store-from-rfq');
    Route::post('purchase-orders/mfo-approved/{id}', '\Revlv\Controllers\Procurements\PurchaseOrderController@mfoApproved')->name('purchase-orders.mfo-approved');
    Route::post('purchase-orders/pcco-approved/{id}', '\Revlv\Controllers\Procurements\PurchaseOrderController@pccoApproved')->name('purchase-orders.pcco-approved');
    Route::resource('purchase-orders', '\Revlv\Controllers\Procurements\PurchaseOrderController');

    /*
    |--------------------------------------------------------------------------
    | NTP Routes
    |--------------------------------------------------------------------------
    |
    */
    Route::put('ntp/update-signatory/{id}', '\Revlv\Controllers\Procurements\NoticeToProceedController@updateSignatory')->name('ntp.update-signatory');
    Route::get('ntp/print/{id}', '\Revlv\Controllers\Procurements\NoticeToProceedController@viewPrint')->name('ntp.print');
    Route::resource('ntp', '\Revlv\Controllers\Procurements\NoticeToProceedController');

    /*
    |--------------------------------------------------------------------------
    | Delivery Routes
    |--------------------------------------------------------------------------
    |
    */
    Route::get('delivery-orders/print/{id}', '\Revlv\Controllers\Procurements\DeliveryController@viewPrint')->name('delivery-orders.print');
    Route::put('delivery-orders/update-signatory/{id}', '\Revlv\Controllers\Procurements\DeliveryController@updateSignatory')->name('delivery-orders.update-signatory');
    Route::post('delivery-orders/create-purchase/{id}', '\Revlv\Controllers\Procurements\DeliveryController@createFromPurchase')->name('delivery-orders.create-purchase');
    Route::get('delivery-orders/completed/{id}', '\Revlv\Controllers\Procurements\DeliveryController@completeOrder')->name('delivery-orders.completed');
    Route::resource('delivery-orders', '\Revlv\Controllers\Procurements\DeliveryController');

    /*
    |--------------------------------------------------------------------------
    | Inspection Routes
    |--------------------------------------------------------------------------
    |
    */
    Route::get('inspection-and-acceptance/print/{id}', '\Revlv\Controllers\Procurements\InspectionAndAcceptanceController@viewPrint')->name('inspection-and-acceptance.print');
    Route::post('inspection-and-acceptance/create-from-delivery/{id}', '\Revlv\Controllers\Procurements\InspectionAndAcceptanceController@storeFromDelivery')->name('inspection-and-acceptance.create-from-delivery.store');
    Route::get('inspection-and-acceptance/create-from-delivery/{id}', '\Revlv\Controllers\Procurements\InspectionAndAcceptanceController@createFromDelivery')->name('inspection-and-acceptance.create-from-delivery');
    Route::get('inspection-and-acceptance/accepted/{id}', '\Revlv\Controllers\Procurements\InspectionAndAcceptanceController@acceptOrder')->name('inspection-and-acceptance.accepted');
    Route::put('inspection-and-acceptance/update-signatory/{id}', '\Revlv\Controllers\Procurements\InspectionAndAcceptanceController@updateSignatory')->name('inspection-and-acceptance.update-signatory');
    Route::resource('inspection-and-acceptance', '\Revlv\Controllers\Procurements\InspectionAndAcceptanceController');

    Route::post('delivery-to-coa/proceed/{id}', '\Revlv\Controllers\Procurements\DeliveryToCoaController@proceedDelivery')->name('delivery-to-coa.proceed');
    Route::resource('delivery-to-coa', '\Revlv\Controllers\Procurements\DeliveryToCoaController');

    /*
    |--------------------------------------------------------------------------
    | Delivered Inspection Routes
    |--------------------------------------------------------------------------
    |
    */
    Route::get('delivered-inspections/print/{id}', '\Revlv\Controllers\Procurements\DeliveredInspectionReportController@viewPrint')->name('delivered-inspections.print');
    Route::post('delivered-inspections/add-issue/{id}', '\Revlv\Controllers\Procurements\DeliveredInspectionReportController@addIssue')->name('delivered-inspections.add-issue');
    Route::post('delivered-inspections/start-inspection/{id}', '\Revlv\Controllers\Procurements\DeliveredInspectionReportController@startInspection')->name('delivered-inspections.start-inspection');
    Route::post('delivered-inspections/close-inspection/{id}', '\Revlv\Controllers\Procurements\DeliveredInspectionReportController@closeInspection')->name('delivered-inspections.close-inspection');
    Route::resource('delivered-inspections', '\Revlv\Controllers\Procurements\DeliveredInspectionReportController');

    /*
    |--------------------------------------------------------------------------
    | Voucher Routes
    |--------------------------------------------------------------------------
    |
    */
    Route::post('vouchers/released/{id}', '\Revlv\Controllers\Procurements\VoucherController@releasePayment')->name('vouchers.released');
    Route::post('vouchers/received/{id}', '\Revlv\Controllers\Procurements\VoucherController@receivePayment')->name('vouchers.received');
    Route::resource('vouchers', '\Revlv\Controllers\Procurements\VoucherController');
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
    Route::resource('catered-units', '\Revlv\Controllers\Settings\CateredUnitController');

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
    Route::get('users/archives', '\Revlv\Controllers\Sentinel\UserController@archives')->name('users.archives');
    Route::resource('users', '\Revlv\Controllers\Sentinel\UserController');
    Route::resource('roles', '\Revlv\Controllers\Sentinel\RoleController');
    Route::resource('permissions', '\Revlv\Controllers\Sentinel\PermissionController');
    Route::resource('user/groups', '\Revlv\Controllers\Sentinel\UserGroupController');


    Route::post('suppliers/accepts/{id}', '\Revlv\Controllers\Settings\SupplierController@acceptSupplier')->name('suppliers.accepts');
    Route::get('suppliers/drafts', '\Revlv\Controllers\Settings\SupplierController@drafts')->name('suppliers.drafts');
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
    Route::get('users-trashed', '\Revlv\Controllers\Sentinel\UserController@getArchivedDatatable')->name('users-trashed');

    /*
    |--------------------------------------------------------------------------
    | Settings Routes
    |--------------------------------------------------------------------------
    |
    */
    Route::get('supplier-drafts', '\Revlv\Controllers\Settings\SupplierController@getDraftDatatable')->name('settings.supplier-drafts');
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
    Route::get('catered-units', '\Revlv\Controllers\Settings\CateredUnitController@getDatatable')->name('maintenance.catered-units');

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
    Route::get('vouchers', '\Revlv\Controllers\Procurements\VoucherController@getDatatable')->name('procurements.vouchers');

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