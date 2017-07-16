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

Route::get('chat', function(){
    return view('chat');
});

Route::get('/messages', function(){
    return \Revlv\Chats\Message\MessageEloquent::all();
});

Route::group(['as' => 'reports.', 'prefix' => 'reports'], function () {
    Route::get('psr/download/{search_at?}', '\Revlv\Controllers\Reports\PSRController@download')->name('reports.psr-transactions.download');
    Route::resource('psr-transactions', '\Revlv\Controllers\Reports\PSRController');

    Route::get('transaction-days/download/{search_at?}', '\Revlv\Controllers\Reports\TransactionDayController@download')->name('reports.transaction-days.download');
    Route::get('transaction-days/download/{search_at?}', '\Revlv\Controllers\Reports\TransactionDayController@download')->name('reports.transaction-days.download');
    Route::resource('transaction-days', '\Revlv\Controllers\Reports\TransactionDayController');

    Route::resource('suppliers', '\Revlv\Controllers\Reports\TransactionDayController');
    Route::get('uprs/{programs}/{centers}/{type}', '\Revlv\Controllers\Reports\UPRController@getUprs')->name('reports.uprs');
    Route::get('upr-centers/{id}/{type}', '\Revlv\Controllers\Reports\UPRController@getUprCenters')->name('reports.upr-centers');
    Route::get('upr-programs/{type}', '\Revlv\Controllers\Reports\UPRController@getUprPrograms')->name('reports.upr-analytics');
    Route::get('programs/{type}', '\Revlv\Controllers\Reports\UPRController@getPrograms')->name('reports.programs');
    Route::get('centers/{id}/{type}', '\Revlv\Controllers\Reports\UPRController@getCenters')->name('reports.centers');
    Route::get('units/{programs}/{id}/{type}', '\Revlv\Controllers\Reports\UPRController@getUnits')->name('reports.units');
});

/*
|--------------------------------------------------------------------------
| notifications Routes
|--------------------------------------------------------------------------
|
*/
Route::resource('notifications', '\Revlv\Controllers\Notifications\NotificationController');
Route::resource('change-logs', '\Revlv\Controllers\Notifications\ChangeLogsController');

/*
|--------------------------------------------------------------------------
| Biddings Routes
|--------------------------------------------------------------------------
|
*/
Route::group(['as' => 'biddings.', 'prefix' => 'biddings'], function () {
    Route::resource('unit-purchase-requests', '\Revlv\Controllers\Biddings\UPRController');

    Route::get('document-acceptance/create/{id}', '\Revlv\Controllers\Biddings\DocumentAcceptanceController@create')->name('document-acceptance.create-by-rfq');

    Route::get('bid-openings/closed/{id}', '\Revlv\Controllers\Biddings\BidOpeningController@closed')->name('bid-openings.closed');
    Route::get('pre-bids/create/{id}', '\Revlv\Controllers\Biddings\PreBidController@create')->name('pre-bids.create-by-upr');

    Route::get('proponents/{id}/{proponent}', '\Revlv\Controllers\Biddings\BidDocsController@show')->name('proponents.show');

    Route::resource('philgeps', '\Revlv\Controllers\Biddings\PhilGepsController');
    Route::resource('bid-docs', '\Revlv\Controllers\Biddings\BidDocsController');
    Route::resource('bid-openings', '\Revlv\Controllers\Biddings\BidOpeningController');
    Route::resource('document-acceptance', '\Revlv\Controllers\Biddings\DocumentAcceptanceController');
    Route::resource('finalize-bid-docs', '\Revlv\Controllers\Biddings\FinalBidDocsController');
    Route::resource('itb', '\Revlv\Controllers\Biddings\InvitationToBidController');
    Route::resource('post-qualifications', '\Revlv\Controllers\Biddings\PostQualificationController');
    Route::resource('pre-bids', '\Revlv\Controllers\Biddings\PreBidController');

});

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
    Route::put('unit-purchase-requests/update-signatories/{id}', '\Revlv\Controllers\Procurements\UPRController@updateSignatory')->name('unit-purchase-requests.update-signatories');
    Route::put('unit-purchase-requests/cancelled/{id}', '\Revlv\Controllers\Procurements\UPRController@cancelled')->name('unit-purchase-requests.cancelled');
    Route::get('unit-purchase-requests/second-step', '\Revlv\Controllers\Procurements\UPRController@secondStep')->name('unit-purchase-requests.second-step');
    Route::get('unit-purchase-requests/print/{id}', '\Revlv\Controllers\Procurements\UPRController@viewPrint')->name('unit-purchase-requests.print');
    Route::post('unit-purchase-requests/terminate/{id}', '\Revlv\Controllers\Procurements\UPRController@terminateUPR')->name('unit-purchase-requests.terminate');
    Route::post('unit-purchase-requests/attachments/{id}', '\Revlv\Controllers\Procurements\UPRController@uploadAttachment')->name('unit-purchase-requests.attachments.store');
    Route::get('unit-purchase-requests/timelines/{id}', '\Revlv\Controllers\Procurements\UPRController@timelines')->name('unit-purchase-requests.timelines');
    Route::get('unit-purchase-requests/download/{id}', '\Revlv\Controllers\Procurements\UPRController@downloadAttachment')->name('unit-purchase-requests.attachments.download');
    Route::get('unit-purchase-requests/logs/{id}', '\Revlv\Controllers\Procurements\UPRController@viewLogs')->name('unit-purchase-requests.logs');
    Route::get('unit-purchase-requests/imports', '\Revlv\Controllers\Procurements\UPRController@uploadView')->name('unit-purchase-requests.imports');
    Route::post('unit-purchase-requests/import-file', '\Revlv\Controllers\Procurements\UPRController@uploadFile')->name('unit-purchase-requests.import-file');
    Route::post('unit-purchase-requests/save-file', '\Revlv\Controllers\Procurements\UPRController@saveFile')->name('unit-purchase-requests.save-file');
    Route::resource('unit-purchase-requests', '\Revlv\Controllers\Procurements\UPRController');

    /*
    |--------------------------------------------------------------------------
    | RFQ Routes
    |--------------------------------------------------------------------------
    |
    */
    Route::get('blank-rfq/logs/{id}', '\Revlv\Controllers\Procurements\BlankRFQController@viewLogs')->name('blank-rfq.logs');
    Route::get('blank-rfq/print/{id}', '\Revlv\Controllers\Procurements\BlankRFQController@viewPrint')->name('blank-rfq.print');
    Route::get('rfq/get-info/{id}', '\Revlv\Controllers\Procurements\BlankRFQController@getInfo')->name('rfq.get-info');
    Route::post('blank-rfq/closed', '\Revlv\Controllers\Procurements\BlankRFQController@closed')->name('blank-rfq.closed');
    Route::resource('blank-rfq', '\Revlv\Controllers\Procurements\BlankRFQController');

    /*
    |--------------------------------------------------------------------------
    | PhilGeps Routes
    |--------------------------------------------------------------------------
    |
    */
    Route::get('philgeps-posting/logs/{id}', '\Revlv\Controllers\Procurements\PhilGepsPostingController@viewLogs')->name('philgeps-posting.logs');
    Route::post('philgeps-posting/attachments/{id}', '\Revlv\Controllers\Procurements\PhilGepsPostingController@uploadAttachment')->name('philgeps-posting.attachments.store');
    Route::get('philgeps-posting/download/{id}', '\Revlv\Controllers\Procurements\PhilGepsPostingController@downloadAttachment')->name('philgeps-posting.attachments.download');
    Route::resource('philgeps-posting', '\Revlv\Controllers\Procurements\PhilGepsPostingController');

    /*
    |--------------------------------------------------------------------------
    | ISPQ Routes
    |--------------------------------------------------------------------------
    |
    */
    Route::get('ispq/logs/{id}', '\Revlv\Controllers\Procurements\ISPQController@viewLogs')->name('ispq.logs');
    Route::get('ispq/print/{id}', '\Revlv\Controllers\Procurements\ISPQController@viewPrint')->name('ispq.print');
    Route::post('ispq/store-by-rfq/{id}', '\Revlv\Controllers\Procurements\ISPQController@createByRFQ')->name('ispq.store-by-rfq');
    Route::resource('ispq', '\Revlv\Controllers\Procurements\ISPQController');

    /*
    |--------------------------------------------------------------------------
    | Canvass Routes
    |--------------------------------------------------------------------------
    |
    */
    Route::get('canvassing/logs/{id}', '\Revlv\Controllers\Procurements\CanvassingController@viewLogs')->name('canvassing.logs');
    Route::get('canvassing/print/{id}', '\Revlv\Controllers\Procurements\CanvassingController@viewPrint')->name('canvassing.print');
    Route::post('canvassing/signatories/{id}', '\Revlv\Controllers\Procurements\CanvassingController@addSignatories')->name('canvassing.signatories');
    Route::post('canvassing/opening/{id}', '\Revlv\Controllers\Procurements\CanvassingController@openCanvass')->name('canvassing.opening');
    Route::resource('canvassing', '\Revlv\Controllers\Procurements\CanvassingController');

    /*
    |--------------------------------------------------------------------------
    | Minutes Routes
    |--------------------------------------------------------------------------
    |
    */
    Route::get('minutes/print-resolution/{id}', '\Revlv\Controllers\Procurements\MinutesController@viewResolution')->name('minutes.print-resolution');
    Route::get('minutes/print/{id}', '\Revlv\Controllers\Procurements\MinutesController@viewPrint')->name('minutes.print');
    Route::resource('minutes', '\Revlv\Controllers\Procurements\MinutesController');

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
    Route::get('noa/logs/{id}', '\Revlv\Controllers\Procurements\NoticeOfAwardController@viewLogs')->name('noa.logs');
    Route::post('noa/accepted', '\Revlv\Controllers\Procurements\NoticeOfAwardController@acceptNOA')->name('noa.accepted');
    Route::put('noa/update-dates/{id}', '\Revlv\Controllers\Procurements\NoticeOfAwardController@updateDates')->name('noa.update-dates');
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
    Route::get('purchase-orders/logs/{id}', '\Revlv\Controllers\Procurements\PurchaseOrderController@viewLogs')->name('purchase-orders.logs');
    Route::get('purchase-orders/print/{id}', '\Revlv\Controllers\Procurements\PurchaseOrderController@viewPrint')->name('purchase-orders.print');
    Route::get('purchase-orders/print-terms/{id}', '\Revlv\Controllers\Procurements\PurchaseOrderController@viewPrintTerms')->name('purchase-orders.print-terms');
    Route::get('purchase-orders/print-coa/{id}', '\Revlv\Controllers\Procurements\PurchaseOrderController@viewPrintCOA')->name('purchase-orders.print-coa');
    Route::get('purchase-orders/rfq/{rfq_id}', '\Revlv\Controllers\Procurements\PurchaseOrderController@createFromRfq')->name('purchase-orders.rfq');
    Route::get('purchase-orders/coa-file/{id}', '\Revlv\Controllers\Procurements\PurchaseOrderController@downloadCoa')->name('purchase-orders.coa-file');
    Route::put('purchase-orders/update-dates/{id}', '\Revlv\Controllers\Procurements\PurchaseOrderController@updateDates')->name('purchase-orders.update-dates');
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
    Route::get('ntp/logs/{id}', '\Revlv\Controllers\Procurements\NoticeToProceedController@viewLogs')->name('ntp.logs');
    Route::put('ntp/update-dates/{id}', '\Revlv\Controllers\Procurements\NoticeToProceedController@updateDates')->name('ntp.update-dates');
    Route::put('ntp/update-signatory/{id}', '\Revlv\Controllers\Procurements\NoticeToProceedController@updateSignatory')->name('ntp.update-signatory');
    Route::get('ntp/print/{id}', '\Revlv\Controllers\Procurements\NoticeToProceedController@viewPrint')->name('ntp.print');
    Route::resource('ntp', '\Revlv\Controllers\Procurements\NoticeToProceedController');

    /*
    |--------------------------------------------------------------------------
    | Delivery Routes
    |--------------------------------------------------------------------------
    |
    */
    Route::get('delivery-orders/store-by-dr/{id}', '\Revlv\Controllers\Procurements\DeliveredInspectionReportController@storeByDR')->name('delivery-orders.store-by-dr');
    Route::get('delivery-orders/edit-dates/{id}', '\Revlv\Controllers\Procurements\DeliveryController@editDates')->name('delivery-orders.edit-dates');
    Route::get('delivery-orders/logs/{id}', '\Revlv\Controllers\Procurements\DeliveryController@viewLogs')->name('delivery-orders.logs');
    Route::get('delivery-orders/print/{id}', '\Revlv\Controllers\Procurements\DeliveryController@viewPrint')->name('delivery-orders.print');
    Route::put('delivery-orders/update-dates/{id}', '\Revlv\Controllers\Procurements\DeliveryController@updateDates')->name('delivery-orders.update-dates');
    Route::put('delivery-orders/update-signatory/{id}', '\Revlv\Controllers\Procurements\DeliveryController@updateSignatory')->name('delivery-orders.update-signatory');
    Route::post('delivery-orders/create-purchase/{id}', '\Revlv\Controllers\Procurements\DeliveryController@createFromPurchase')->name('delivery-orders.create-purchase');
    Route::post('delivery-orders/completed/{id}', '\Revlv\Controllers\Procurements\DeliveryController@completeOrder')->name('delivery-orders.completed');
    Route::resource('delivery-orders', '\Revlv\Controllers\Procurements\DeliveryController');

    /*
    |--------------------------------------------------------------------------
    | Inspection Routes
    |--------------------------------------------------------------------------
    |
    */
    Route::get('inspection-and-acceptance/logs/{id}', '\Revlv\Controllers\Procurements\InspectionAndAcceptanceController@viewLogs')->name('inspection-and-acceptance.logs');
    Route::get('inspection-and-acceptance/print/{id}', '\Revlv\Controllers\Procurements\InspectionAndAcceptanceController@viewPrint')->name('inspection-and-acceptance.print');
    Route::post('inspection-and-acceptance/create-from-delivery/{id}', '\Revlv\Controllers\Procurements\InspectionAndAcceptanceController@storeFromDelivery')->name('inspection-and-acceptance.create-from-delivery.store');
    Route::get('inspection-and-acceptance/create-from-delivery/{id}', '\Revlv\Controllers\Procurements\InspectionAndAcceptanceController@createFromDelivery')->name('inspection-and-acceptance.create-from-delivery');
    Route::post('inspection-and-acceptance/accepted/{id}', '\Revlv\Controllers\Procurements\InspectionAndAcceptanceController@acceptOrder')->name('inspection-and-acceptance.accepted');
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
    Route::get('delivered-inspections/logs/{id}', '\Revlv\Controllers\Procurements\DeliveredInspectionReportController@viewLogs')->name('delivered-inspections.logs');
    Route::put('delivered-inspections/update-signatory/{id}', '\Revlv\Controllers\Procurements\DeliveredInspectionReportController@updateSignatory')->name('delivered-inspections.update-signatory');
    Route::get('delivered-inspections/print/{id}', '\Revlv\Controllers\Procurements\DeliveredInspectionReportController@viewPrint')->name('delivered-inspections.print');
    Route::post('delivered-inspections/add-issue/{id}', '\Revlv\Controllers\Procurements\DeliveredInspectionReportController@addIssue')->name('delivered-inspections.add-issue');
    Route::post('delivered-inspections/start-inspection/{id}', '\Revlv\Controllers\Procurements\DeliveredInspectionReportController@startInspection')->name('delivered-inspections.start-inspection');
    Route::post('delivered-inspections/close-inspection/{id}', '\Revlv\Controllers\Procurements\DeliveredInspectionReportController@closeInspection')->name('delivered-inspections.close-inspection');
    Route::post('delivered-inspections/corrected/{id}', '\Revlv\Controllers\Procurements\DeliveredInspectionReportController@correctedIssue')->name('delivered-inspections.corrected');
    Route::resource('delivered-inspections', '\Revlv\Controllers\Procurements\DeliveredInspectionReportController');

    /*
    |--------------------------------------------------------------------------
    | Voucher Routes
    |--------------------------------------------------------------------------
    |
    */
    Route::put('vouchers/update-signatories/{id}', '\Revlv\Controllers\Procurements\VoucherController@updateSignatory')->name('vouchers.update-signatories');
    Route::get('vouchers/print/{id}', '\Revlv\Controllers\Procurements\VoucherController@viewPrint')->name('vouchers.print');
    Route::get('vouchers/logs/{id}', '\Revlv\Controllers\Procurements\VoucherController@viewLogs')->name('vouchers.logs');
    Route::post('vouchers/preaudit/{id}', '\Revlv\Controllers\Procurements\VoucherController@preauditVoucher')->name('vouchers.preaudit');
    Route::post('vouchers/certify/{id}', '\Revlv\Controllers\Procurements\VoucherController@certifyVoucher')->name('vouchers.certify');
    Route::post('vouchers/journal/{id}', '\Revlv\Controllers\Procurements\VoucherController@journalVoucher')->name('vouchers.journal');
    Route::post('vouchers/approved/{id}', '\Revlv\Controllers\Procurements\VoucherController@approvedVoucher')->name('vouchers.approved');
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
    Route::resource('procurement-types', '\Revlv\Controllers\Settings\ProcurementTypeController');
    Route::resource('procurement-centers', '\Revlv\Controllers\Settings\ProcurementCenterController');
    Route::resource('account-codes', '\Revlv\Controllers\Settings\AccountCodeController');
    Route::resource('chargeability', '\Revlv\Controllers\Settings\ChargeabilityController');
    Route::resource('payment-terms', '\Revlv\Controllers\Settings\PaymentTermController');
    Route::resource('units', '\Revlv\Controllers\Settings\UnitController');
    Route::resource('banks', '\Revlv\Controllers\Settings\BankController');
    Route::resource('announcements', '\Revlv\Controllers\Settings\AnnouncementController');
    Route::resource('catered-units', '\Revlv\Controllers\Settings\CateredUnitController');
    Route::resource('bacsec', '\Revlv\Controllers\Settings\BacSecController');

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
    Route::resource('holidays', '\Revlv\Controllers\Settings\HolidayController');
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
    Route::get('request-for-bids', '\Revlv\Controllers\Biddings\RFBController@getDatatable')->name('biddings.request-for-bids');
    Route::get('noa-acceptance', '\Revlv\Controllers\Biddings\NOAController@getDatatable')->name('biddings.noa-acceptance');

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
    Route::get('holidays', '\Revlv\Controllers\Settings\HolidayController@getDatatable')->name('settings.holidays');

    /*
    |--------------------------------------------------------------------------
    | Reports Routes
    |--------------------------------------------------------------------------
    |
    */
    Route::get('psr-transactions', '\Revlv\Controllers\Reports\PSRController@getDatatable')->name('reports.psr-transactions');
    Route::get('transaction-days', '\Revlv\Controllers\Reports\TransactionDayController@getDatatable')->name('reports.transaction-days');

    /*
    |--------------------------------------------------------------------------
    | Maintenance Routes
    |--------------------------------------------------------------------------
    |
    */
    Route::get('mode-of-procurements', '\Revlv\Controllers\Settings\ModeOfProcurementsController@getDatatable')->name('maintenance.mode-of-procurements');
    Route::get('procurement-centers', '\Revlv\Controllers\Settings\ProcurementCenterController@getDatatable')->name('maintenance.procurement-centers');
    Route::get('procurement-types', '\Revlv\Controllers\Settings\ProcurementTypeController@getDatatable')->name('maintenance.procurement-types');
    Route::get('account-codes', '\Revlv\Controllers\Settings\AccountCodeController@getDatatable')->name('maintenance.account-codes');
    Route::get('chargeability', '\Revlv\Controllers\Settings\ChargeabilityController@getDatatable')->name('maintenance.chargeability');
    Route::get('payment-terms', '\Revlv\Controllers\Settings\PaymentTermController@getDatatable')->name('maintenance.payment-terms');
    Route::get('units', '\Revlv\Controllers\Settings\UnitController@getDatatable')->name('maintenance.units');
    Route::get('banks', '\Revlv\Controllers\Settings\BankController@getDatatable')->name('maintenance.banks');
    Route::get('announcements', '\Revlv\Controllers\Settings\AnnouncementController@getDatatable')->name('maintenance.announcements');
    Route::get('catered-units', '\Revlv\Controllers\Settings\CateredUnitController@getDatatable')->name('maintenance.catered-units');
    Route::get('bacsec', '\Revlv\Controllers\Settings\BacSecController@getDatatable')->name('maintenance.bacsec');

    /*
    |--------------------------------------------------------------------------
    | Procurement Routes
    |--------------------------------------------------------------------------
    |
    */
    Route::get('procurements.unit-purchase-requests', '\Revlv\Controllers\Procurements\UPRController@getDatatable')->name('procurements.unit-purchase-request');
    Route::get('biddings.unit-purchase-requests', '\Revlv\Controllers\Biddings\UPRController@getDatatable')->name('biddings.unit-purchase-request');
    Route::get('blank-rfq', '\Revlv\Controllers\Procurements\BlankRFQController@getDatatable')->name('procurements.blank-rfq');
    Route::get('philgeps-posting', '\Revlv\Controllers\Procurements\PhilGepsPostingController@getDatatable')->name('procurements.philgeps-posting');
    Route::get('ispq', '\Revlv\Controllers\Procurements\ISPQController@getDatatable')->name('procurements.ispq');
    Route::get('canvassing', '\Revlv\Controllers\Procurements\CanvassingController@getDatatable')->name('procurements.canvassing');
    Route::get('minutes', '\Revlv\Controllers\Procurements\MinutesController@getDatatable')->name('procurements.minutes');
    Route::get('noa', '\Revlv\Controllers\Procurements\NoticeOfAwardController@getDatatable')->name('procurements.noa');
    Route::get('ntp', '\Revlv\Controllers\Procurements\NoticeToProceedController@getDatatable')->name('procurements.ntp');
    Route::get('purchase-orders', '\Revlv\Controllers\Procurements\PurchaseOrderController@getDatatable')->name('procurements.purchase-orders');
    Route::get('delivery-orders', '\Revlv\Controllers\Procurements\DeliveryController@getDatatable')->name('procurements.delivery-orders');
    Route::get('inspection-and-acceptance', '\Revlv\Controllers\Procurements\InspectionAndAcceptanceController@getDatatable')->name('procurements.inspection-and-acceptance');

    Route::get('delivery-to-coa', '\Revlv\Controllers\Procurements\DeliveryToCoaController@getDatatable')->name('procurements.delivery-to-coa');
    Route::get('delivered-inspections', '\Revlv\Controllers\Procurements\DeliveredInspectionReportController@getDatatable')->name('procurements.delivered-inspections');
    Route::get('vouchers', '\Revlv\Controllers\Procurements\VoucherController@getDatatable')->name('procurements.vouchers');


    /*
    |--------------------------------------------------------------------------
    | Bidding Routes
    |--------------------------------------------------------------------------
    |
    |
    */
    Route::get('document-acceptance', '\Revlv\Controllers\Biddings\DocumentAcceptanceController@getDatatable')->name('biddings.document-acceptance');
    Route::get('itb', '\Revlv\Controllers\Biddings\InvitationToBidController@getDatatable')->name('biddings.itb');
    Route::get('philgeps', '\Revlv\Controllers\Biddings\PhilGepsController@getDatatable')->name('biddings.philgeps');
    Route::get('bid-docs', '\Revlv\Controllers\Biddings\BidDocsController@getDatatable')->name('biddings.bid-docs');
    Route::get('pre-bids', '\Revlv\Controllers\Biddings\PreBidController@getDatatable')->name('biddings.pre-bids');
    Route::get('bid-openings', '\Revlv\Controllers\Biddings\BidOpeningController@getDatatable')->name('biddings.bid-openings');

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