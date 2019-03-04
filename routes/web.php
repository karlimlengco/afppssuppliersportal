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
Route::get('overview/print/{type}', '\Revlv\Controllers\DashboardController@viewPrint')->name('overview.print');
Route::get('overview/program/print/{program}/{type}', '\Revlv\Controllers\DashboardController@viewPrintProgram')->name('overview.program');
Route::get('overview/program-center/print/{program}/{center}/{type}', '\Revlv\Controllers\DashboardController@viewPrintProgramCenter')->name('overview.program-center');
Route::get('overview/uprs/print/{program}/{center}/{type}', '\Revlv\Controllers\DashboardController@viewPrintUpr')->name('overview.upr');

Route::post('/', '\Revlv\Controllers\DashboardController@store')->name('dashboard.store');
Route::post('/guard/auth/broadcasting', '\Revlv\Controllers\ChatController@authenticate')->name('messages.auth');
Route::get('/', '\Revlv\Controllers\DashboardController@index')->name('dashboard.index');

Route::get('pdf/footer', '\Revlv\Controllers\PDFController@getFooter')->name('pdf.footer');

Route::get('chat', '\Revlv\Controllers\ChatController@index')->name('messages.index');
Route::post('messages', '\Revlv\Controllers\Chat\MessageController@store')->name('messages.store');

Route::get('upr-drafts', '\Revlv\Controllers\Procurements\UPRController@drafts')->name('upr-drafts.index');
Route::post('procurements/upr-drafts/confirm/{id}', '\Revlv\Controllers\Procurements\UPRController@confirmDrafts')->name('procurements.upr-drafts.confirm');
Route::get('messages', '\Revlv\Controllers\Chat\MessageController@getMessage')->name('messages.index');
Route::get('admin-messages-view', '\Revlv\Controllers\Chat\MessageController@getAdminMessage')->name('messages.admin');
Route::get('admin-messages/api', '\Revlv\Controllers\Chat\MessageController@getAdminMessageAPI')->name('messages.admin.api');
Route::get('messages/{sender}/{receiver?}', '\Revlv\Controllers\Chat\MessageController@showChat')->name('messages.show');
Route::get('upr-messages/{upr}', '\Revlv\Controllers\Chat\MessageController@showUPRChat')->name('messages.show');

Route::get('api/get/new_code/{id}', '\Revlv\Controllers\Settings\AccountCodeController@getCode')->name('settings.account-codes.get-code');

// create new signatory
Route::post('api/signatories/store', '\Revlv\Controllers\Settings\SignatoryController@storeNew')->name('api.signatory.store');
Route::post('procurements.upr-items.import-item/{upr}', '\Revlv\Controllers\Procurements\UPRController@importItem')->name('procurements.upr-items.import');
Route::get('procurements/upr-items/delete/{upr}', '\Revlv\Controllers\Procurements\UPRController@itemDelete')->name('procurements.upr-items.destroy');
Route::post('procurements/upr-items/{upr}', '\Revlv\Controllers\Procurements\UPRController@itemStore')->name('procurements.upr-items.store');

Route::get('api/suppliers/info/{id}', '\Revlv\Controllers\Settings\SupplierController@getInfo')->name('api.signatory.info');
Route::post('api/suppliers/store', '\Revlv\Controllers\Settings\SupplierController@storeNew')->name('api.signatory.store');

Route::get('api/signatories/lists', '\Revlv\Controllers\Settings\SignatoryController@getLists')->name('api.signatory.lists');
// create new signatory


Route::get('procurements/unit-purchase-requests/overview/delay/{programs}/{pcco?}/{unit?}', '\Revlv\Controllers\Procurements\UPRController@overviewDelay')->name('upr-overview.delay');
Route::get('procurements/unit-purchase-requests/overview/ongoing/{programs}/{pcco?}/{unit?}', '\Revlv\Controllers\Procurements\UPRController@overviewOngoing')->name('upr-overview.ongoing');
Route::get('procurements/unit-purchase-requests/overview/cancelled/{programs}/{pcco?}/{unit?}', '\Revlv\Controllers\Procurements\UPRController@overviewCancelled')->name('upr-overview.cancelled');
Route::get('procurements/unit-purchase-requests/overview/completed/{programs}/{pcco?}/{unit?}', '\Revlv\Controllers\Procurements\UPRController@overviewCompleted')->name('upr-overview.completed');


Route::get('timelines/print/{id}', '\Revlv\Controllers\Reports\UPRController@downloadTimeline')->name('settings.account-codes.get-code');

Route::group(['as' => 'api.', 'prefix' => 'api'], function () {
    Route::get('users', '\Revlv\Controllers\Sentinel\UserController@userLists')->name('users');
    Route::get('user-message/{id}', '\Revlv\Controllers\Chat\MessageController@getChatMessageBySender')->name('users');
    Route::get('upr-message/{id}', '\Revlv\Controllers\Chat\MessageController@getChatMessageByUPR')->name('users');

});
// Route::get('messages', function(){
//     return \Revlv\Chats\Message\MessageEloquent::with('user')->get();
// });

Route::group(['as' => 'reports.', 'prefix' => 'reports'], function () {

    Route::get('supplier-eligibilities', '\Revlv\Controllers\Reports\SupplierController@index')->name('supplier-eligibilities.index');


    Route::get('unit-psr/{type}', '\Revlv\Controllers\Reports\UnitPSRController@getUnitPsr')->name('reports.unit-psr');
    // Route::get('pcco-psr/{type}', '\Revlv\Controllers\Reports\UnitPSRController@getPccoPsr')->name('reports.pcco-psr');
    Route::get('pcco-psr', '\Revlv\Controllers\Reports\PSRController@PccoPSR')->name('reports.pcco-psr');

    Route::get('pcco-psr-items/{type}/{pcco}', '\Revlv\Controllers\Reports\PSRController@getPccoPsrItem')->name('reports.pcco-psr-items');
    Route::get('unit-psr-items/{type}/{unit}', '\Revlv\Controllers\Reports\UnitPSRController@getUnitPsrItem')->name('reports.unit-psr-items');

    Route::get('psr/download/{search_at?}', '\Revlv\Controllers\Reports\PSRController@download')->name('reports.psr-transactions.download');
    Route::get('unit-psr/download/{search_at?}', '\Revlv\Controllers\Reports\UnitPSRController@download')->name('reports.unit-psr-transactions.download');

    Route::resource('level-of-compliance', '\Revlv\Controllers\Reports\ComplianceController');
    Route::resource('psr-transactions', '\Revlv\Controllers\Reports\PSRController');
    Route::resource('unit-psr-transactions', '\Revlv\Controllers\Reports\UnitPSRController');

    Route::resource('psr', '\Revlv\Controllers\Reports\PSRReportController');

    Route::get('transaction-days/download/{search_at?}/{type?}', '\Revlv\Controllers\Reports\TransactionDayController@download')->name('reports.transaction-days.download');

    Route::get('transaction-days/download/{search_at?}', '\Revlv\Controllers\Reports\TransactionDayController@download')->name('reports.transaction-days.download');

    Route::get('transaction-psr/download/{search_at?}', '\Revlv\Controllers\Reports\TransactionDayController@downloadPSR')->name('reports.transaction-psr.download');
    Route::get('recapitulations/download', '\Revlv\Controllers\Reports\RecapitulationController@downloadPSR')->name('reports.recapitulations.download');
    Route::get('aggregates/download', '\Revlv\Controllers\Reports\AggregateController@downloadPSR')->name('reports.aggregates.download');
    Route::get('consolidated/download', '\Revlv\Controllers\Reports\ConsolidatedController@downloadPSR')->name('reports.consolidated.download');

    Route::resource('transaction-days', '\Revlv\Controllers\Reports\TransactionDayController');
    Route::resource('recapitulations', '\Revlv\Controllers\Reports\RecapitulationController');
    Route::resource('aggregates', '\Revlv\Controllers\Reports\AggregateController');
    Route::resource('consolidated', '\Revlv\Controllers\Reports\ConsolidatedController');

    Route::resource('suppliers', '\Revlv\Controllers\Reports\TransactionDayController');
    Route::get('uprs/{programs}/{centers}/{type}', '\Revlv\Controllers\Reports\UPRController@getUprs')->name('reports.uprs');
    Route::get('upr-centers/{id}/{type}', '\Revlv\Controllers\Reports\UPRController@getUprCenters')->name('reports.upr-centers');
    Route::get('upr-programs/{type}', '\Revlv\Controllers\Reports\UPRController@getUprPrograms')->name('reports.upr-analytics');
    Route::get('programs/{type}', '\Revlv\Controllers\Reports\UPRController@getPrograms')->name('reports.programs');
    Route::get('centers/{id}/{type}', '\Revlv\Controllers\Reports\UPRController@getCenters')->name('reports.centers');
    Route::get('units/{programs}/{id}/{type}', '\Revlv\Controllers\Reports\UPRController@getUnits')->name('reports.units');

    Route::get('uprs/timeline/{programs}/{centers}/{type}', '\Revlv\Controllers\Reports\UPRController@getPSRUprs')->name('reports.uprs-timeline');
    Route::get('units/timeline/{programs}/{id}/{type}', '\Revlv\Controllers\Reports\UPRController@getPSRUnits')->name('reports.unit-timelines');
    Route::get('upr-centers/timeline/{id}/{type}', '\Revlv\Controllers\Reports\UPRController@getPSRUprCenters')->name('reports.timeline-upr-centers');
    Route::get('timeline/{type}', '\Revlv\Controllers\Reports\UPRController@getPSR')->name('reports.timelines');
    Route::get('program/timeline/{type}', '\Revlv\Controllers\Reports\UPRController@getPSRPrograms')->name('reports.psr-timelines');
});

/*
|--------------------------------------------------------------------------
| notifications Routes
|--------------------------------------------------------------------------
|
*/

Route::put('/unit-purchase-requests/justification/{id}', '\Revlv\Controllers\Procurements\UPRController@addJustification')->name('upr-delays');
Route::get('/upr-delays/api', '\Revlv\Controllers\Notifications\NotificationController@getItems')->name('upr-delays');
Route::resource('notifications', '\Revlv\Controllers\Notifications\NotificationController');
Route::resource('change-logs', '\Revlv\Controllers\Notifications\ChangeLogsController');

/*
|--------------------------------------------------------------------------
| Biddings Routes
|--------------------------------------------------------------------------
|
*/
Route::group(['as' => 'biddings.', 'prefix' => 'biddings'], function () {


    Route::get('unit-purchase-requests/view-cancelled', '\Revlv\Controllers\Biddings\UPRController@viewCancelled')->name('unit-purchase-requests.view-cancel');
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
    Route::get('unit-purchase-requests/imports2', '\Revlv\Controllers\Procurements\UPRController@uploadView2')->name('unit-purchase-requests.imports2');
    Route::post('unit-purchase-requests/import-file', '\Revlv\Controllers\Procurements\UPRController@uploadFile')->name('unit-purchase-requests.import-file');
    Route::post('unit-purchase-requests/import-file2', '\Revlv\Controllers\Procurements\UPRController@uploadFile2')->name('unit-purchase-requests.import-file2');
    Route::post('unit-purchase-requests/save-file', '\Revlv\Controllers\Procurements\UPRController@saveFile')->name('unit-purchase-requests.save-file');
    Route::resource('unit-purchase-requests', '\Revlv\Controllers\Biddings\UPRController');

    Route::get('document-acceptance/create/{id}', '\Revlv\Controllers\Biddings\DocumentAcceptanceController@create')->name('document-acceptance.create-by-rfq');

    Route::post('bid-openings/closed', '\Revlv\Controllers\Biddings\BidOpeningController@closed')->name('bid-openings.closed');
    Route::get('pre-bids/create/{id}', '\Revlv\Controllers\Biddings\PreBidController@create')->name('pre-bids.create-by-upr');

    Route::get('proponents/{id}/{proponent}', '\Revlv\Controllers\Biddings\BidDocsController@show')->name('proponents.show');

    /*
    |--------------------------------------------------------------------------
    | Philgeps Routes
    |--------------------------------------------------------------------------
    |
    */
    Route::get('philgeps/logs/{id}', '\Revlv\Controllers\Biddings\PhilGepsController@viewLogs')->name('philgeps.logs');
    Route::resource('philgeps', '\Revlv\Controllers\Biddings\PhilGepsController');

    /*
    |--------------------------------------------------------------------------
    | Issuance BId Docs Routes
    |--------------------------------------------------------------------------
    |
    */
    Route::post('disqualify-proponent/{id}', '\Revlv\Controllers\Biddings\BidDocsController@disqualify')->name('disqualify-proponent');
    Route::get('bid-docs/delete/{id}', '\Revlv\Controllers\Biddings\BidDocsController@destroy')->name('bid-docs.delete');
    Route::resource('bid-docs', '\Revlv\Controllers\Biddings\BidDocsController');

    /*
    |--------------------------------------------------------------------------
    | Bid Open Routes
    |--------------------------------------------------------------------------
    |
    */
    Route::post('bid-openings/failed', '\Revlv\Controllers\Biddings\BidOpeningController@failed')->name('bid-openings.failed');
    Route::get('bid-openings/logs/{id}', '\Revlv\Controllers\Biddings\BidOpeningController@viewLogs')->name('bid-openings.logs');
    Route::resource('bid-openings', '\Revlv\Controllers\Biddings\BidOpeningController');

    /*
    |--------------------------------------------------------------------------
    | DOcument Accpeptance Routes
    |--------------------------------------------------------------------------
    |
    */

    Route::get('document-acceptance/logs/{id}', '\Revlv\Controllers\Biddings\DocumentAcceptanceController@viewLogs')->name('document-acceptance.logs');
    Route::resource('document-acceptance', '\Revlv\Controllers\Biddings\DocumentAcceptanceController');


    /*
    |--------------------------------------------------------------------------
    | Final Bid DOcs Routes
    |--------------------------------------------------------------------------
    |
    */
    Route::resource('finalize-bid-docs', '\Revlv\Controllers\Biddings\FinalBidDocsController');

    /*
    |--------------------------------------------------------------------------
    | Invitation TO Bid Routes
    |--------------------------------------------------------------------------
    |
    */
    Route::get('itb/logs/{id}', '\Revlv\Controllers\Biddings\InvitationToBidController@viewLogs')->name('itb.logs');
    Route::resource('itb', '\Revlv\Controllers\Biddings\InvitationToBidController');

    /*
    |--------------------------------------------------------------------------
    | PreProc Routes
    |--------------------------------------------------------------------------
    |
    */
    Route::get('preproc/logs/{id}', '\Revlv\Controllers\Biddings\PreProcController@viewLogs')->name('preproc.logs');
    Route::resource('preproc', '\Revlv\Controllers\Biddings\PreProcController');

    /*
    |--------------------------------------------------------------------------
    | Post Qual Routes
    |--------------------------------------------------------------------------
    |
    */
    Route::get('post-qualifications/logs/{id}', '\Revlv\Controllers\Biddings\PostQualificationController@viewLogs')->name('post-qualifications.logs');

    Route::post('post-qualifications/disqualify', '\Revlv\Controllers\Biddings\PostQualificationController@disqualify')->name('post-qualifications.disqualify');

    Route::post('post-qualifications/failed', '\Revlv\Controllers\Biddings\PostQualificationController@failed')->name('post-qualifications.failed');

    Route::post('award-to/{pq_id}/{proponent_id}', '\Revlv\Controllers\Biddings\NOAController@awardToProponent')->name('notice-of-awards.award-to');
    Route::resource('post-qualifications', '\Revlv\Controllers\Biddings\PostQualificationController');

    /*
    |--------------------------------------------------------------------------
    | Prebid Routes
    |--------------------------------------------------------------------------
    |
    */

    Route::post('pre-bids/failed', '\Revlv\Controllers\Biddings\PreBidController@failed')->name('pre-bids.failed');
    Route::get('pre-bids/logs/{id}', '\Revlv\Controllers\Biddings\PreBidController@viewLogs')->name('pre-bids.logs');
    Route::resource('pre-bids', '\Revlv\Controllers\Biddings\PreBidController');

    /*
    |--------------------------------------------------------------------------
    | NOA Routes
    |--------------------------------------------------------------------------
    |
    */


    Route::put('noa/update-dates/{id}', '\Revlv\Controllers\Procurements\NoticeOfAwardController@updateDates')->name('noa.update-dates');
   Route::put('noa/philgeps/{id}', '\Revlv\Controllers\Procurements\NoticeOfAwardController@philgepsPosting')->name('noa.philgeps');
    Route::put('noa/update-signatory/{id}', '\Revlv\Controllers\Procurements\NoticeOfAwardController@updateSignatory')->name('noa.update-signatory');
    Route::put('noa/update-signatory/{id}', '\Revlv\Controllers\Biddings\NOAController@updateSignatory')->name('noa.update-signatory');

    Route::post('noa/performance-bond', '\Revlv\Controllers\Biddings\NOAController@addPerformanceBond')->name('noa.performance-bond');

    Route::resource('noa', '\Revlv\Controllers\Biddings\NOAController');



    /*
    |--------------------------------------------------------------------------
    | PO Routes
    |--------------------------------------------------------------------------
    |
    */
    Route::get('purchase-orders/logs/{id}', '\Revlv\Controllers\Biddings\PurchaseOrderController@viewLogs')->name('purchase-orders.logs');
    Route::get('purchase-orders/print/{id}', '\Revlv\Controllers\Biddings\PurchaseOrderController@viewPrint')->name('purchase-orders.print');
    Route::get('purchase-orders/print2/{id}', '\Revlv\Controllers\Biddings\PurchaseOrderController@viewPrint2')->name('purchase-orders.print');
    Route::get('purchase-orders/print3/{id}', '\Revlv\Controllers\Biddings\PurchaseOrderController@viewPrint3')->name('purchase-orders.print3');
    Route::get('purchase-orders/print-terms/{id}', '\Revlv\Controllers\Biddings\PurchaseOrderController@viewPrintTerms')->name('purchase-orders.print-terms');
    Route::get('purchase-orders/print-coa/{id}', '\Revlv\Controllers\Biddings\PurchaseOrderController@viewPrintCOA')->name('purchase-orders.print-coa');
    Route::get('purchase-orders/rfq/{rfq_id}', '\Revlv\Controllers\Biddings\PurchaseOrderController@createFromRfq')->name('purchase-orders.rfq');
    Route::get('purchase-orders/coa-file/{id}', '\Revlv\Controllers\Biddings\PurchaseOrderController@downloadCoa')->name('purchase-orders.coa-file');
    Route::put('purchase-orders/update-dates/{id}', '\Revlv\Controllers\Biddings\PurchaseOrderController@updateDates')->name('purchase-orders.update-dates');
    Route::post('purchase-orders/coa-approved/{id}', '\Revlv\Controllers\Biddings\PurchaseOrderController@coaApproved')->name('purchase-orders.coa-approved');
    Route::post('purchase-orders/store-from-rfq/{rfq_id}', '\Revlv\Controllers\Biddings\PurchaseOrderController@storeFromRfq')->name('purchase-orders.store-from-rfq');
    Route::post('purchase-orders/mfo-approved/{id}', '\Revlv\Controllers\Biddings\PurchaseOrderController@mfoApproved')->name('purchase-orders.mfo-approved');
    Route::post('purchase-orders/pcco-approved/{id}', '\Revlv\Controllers\Biddings\PurchaseOrderController@pccoApproved')->name('purchase-orders.pcco-approved');
    Route::resource('purchase-orders', '\Revlv\Controllers\Biddings\PurchaseOrderController');

    /*
    |--------------------------------------------------------------------------
    | NTP Routes
    |--------------------------------------------------------------------------
    |
    */
    Route::put('ntp/philgeps/{id}', '\Revlv\Controllers\Biddings\NoticeToProceedController@philgepsPosting')->name('ntp.philgeps');
    Route::get('ntp/logs/{id}', '\Revlv\Controllers\Biddings\NoticeToProceedController@viewLogs')->name('ntp.logs');
    Route::put('ntp/update-dates/{id}', '\Revlv\Controllers\Biddings\NoticeToProceedController@updateDates')->name('ntp.update-dates');
    Route::put('ntp/update-signatory/{id}', '\Revlv\Controllers\Biddings\NoticeToProceedController@updateSignatory')->name('ntp.update-signatory');
    Route::get('ntp/print/{id}', '\Revlv\Controllers\Biddings\NoticeToProceedController@viewPrint')->name('ntp.print');
    Route::resource('ntp', '\Revlv\Controllers\Biddings\NoticeToProceedController');

    /*
    |--------------------------------------------------------------------------
    | Delivery Routes
    |--------------------------------------------------------------------------
    |
    */
    Route::get('delivery-orders/store-by-dr/{id}', '\Revlv\Controllers\Biddings\DeliveredInspectionReportController@storeByDR')->name('delivery-orders.store-by-dr');
    Route::get('delivery-orders/edit-dates/{id}', '\Revlv\Controllers\Biddings\DeliveryController@editDates')->name('delivery-orders.edit-dates');
    Route::get('delivery-orders/logs/{id}', '\Revlv\Controllers\Biddings\DeliveryController@viewLogs')->name('delivery-orders.logs');
    Route::get('delivery-orders/print/{id}', '\Revlv\Controllers\Biddings\DeliveryController@viewPrint')->name('delivery-orders.print');
    Route::put('delivery-orders/update-dates/{id}', '\Revlv\Controllers\Biddings\DeliveryController@updateDates')->name('delivery-orders.update-dates');
    Route::put('delivery-orders/update-signatory/{id}', '\Revlv\Controllers\Biddings\DeliveryController@updateSignatory')->name('delivery-orders.update-signatory');
    Route::post('delivery-orders/create-purchase/{id}', '\Revlv\Controllers\Biddings\DeliveryController@createFromPurchase')->name('delivery-orders.create-purchase');
    Route::post('delivery-orders/completed/{id}', '\Revlv\Controllers\Biddings\DeliveryController@completeOrder')->name('delivery-orders.completed');

    Route::post('delivery-orders/attachments/{id}', '\Revlv\Controllers\Biddings\DeliveryController@uploadAttachment')->name('delivery-orders.attachments.store');

    Route::get('delivery-orders/download/{id}', '\Revlv\Controllers\Biddings\DeliveryController@downloadAttachment')->name('delivery-orders.attachments.download');

    Route::resource('delivery-orders', '\Revlv\Controllers\Biddings\DeliveryController');

    /*
    |--------------------------------------------------------------------------
    | Inspection Routes
    |--------------------------------------------------------------------------
    |
    */
    Route::post('inspection-and-acceptance/invoice/{id}', '\Revlv\Controllers\Biddings\InspectionAndAcceptanceController@addInvoice')->name('inspection-and-acceptance.add-invoice');

    Route::get('inspection-and-acceptance/logs/{id}', '\Revlv\Controllers\Biddings\InspectionAndAcceptanceController@viewLogs')->name('inspection-and-acceptance.logs');
    Route::get('inspection-and-acceptance/print/{id}', '\Revlv\Controllers\Biddings\InspectionAndAcceptanceController@viewPrint')->name('inspection-and-acceptance.print');
    Route::post('inspection-and-acceptance/create-from-delivery/{id}', '\Revlv\Controllers\Biddings\InspectionAndAcceptanceController@storeFromDelivery')->name('inspection-and-acceptance.create-from-delivery.store');
    Route::get('inspection-and-acceptance/create-from-delivery/{id}', '\Revlv\Controllers\Biddings\InspectionAndAcceptanceController@createFromDelivery')->name('inspection-and-acceptance.create-from-delivery');
    Route::post('inspection-and-acceptance/accepted/{id}', '\Revlv\Controllers\Biddings\InspectionAndAcceptanceController@acceptOrder')->name('inspection-and-acceptance.accepted');
    Route::put('inspection-and-acceptance/update-signatory/{id}', '\Revlv\Controllers\Biddings\InspectionAndAcceptanceController@updateSignatory')->name('inspection-and-acceptance.update-signatory');
    Route::resource('inspection-and-acceptance', '\Revlv\Controllers\Biddings\InspectionAndAcceptanceController');

    /*
    |--------------------------------------------------------------------------
    | Delivered Inspection Routes
    |--------------------------------------------------------------------------
    |
    */
    Route::get('delivered-inspections/logs/{id}', '\Revlv\Controllers\Biddings\DeliveredInspectionReportController@viewLogs')->name('delivered-inspections.logs');
    Route::put('delivered-inspections/update-signatory/{id}', '\Revlv\Controllers\Biddings\DeliveredInspectionReportController@updateSignatory')->name('delivered-inspections.update-signatory');
    Route::get('delivered-inspections/print/{id}', '\Revlv\Controllers\Biddings\DeliveredInspectionReportController@viewPrint')->name('delivered-inspections.print');
    Route::post('delivered-inspections/add-issue/{id}', '\Revlv\Controllers\Biddings\DeliveredInspectionReportController@addIssue')->name('delivered-inspections.add-issue');
    Route::post('delivered-inspections/start-inspection/{id}', '\Revlv\Controllers\Biddings\DeliveredInspectionReportController@startInspection')->name('delivered-inspections.start-inspection');
    Route::post('delivered-inspections/close-inspection/{id}', '\Revlv\Controllers\Biddings\DeliveredInspectionReportController@closeInspection')->name('delivered-inspections.close-inspection');
    Route::post('delivered-inspections/corrected/{id}', '\Revlv\Controllers\Biddings\DeliveredInspectionReportController@correctedIssue')->name('delivered-inspections.corrected');
    Route::resource('delivered-inspections', '\Revlv\Controllers\Biddings\DeliveredInspectionReportController');

    /*
    |--------------------------------------------------------------------------
    | Voucher Routes
    |--------------------------------------------------------------------------
    |
    */

   Route::post('vouchers/prepare-payment/{id}', '\Revlv\Controllers\Biddings\VoucherController@preparePaymentVoucher')->name('vouchers.prepare-payment');
   Route::post('vouchers/counter-sign/{id}', '\Revlv\Controllers\Biddings\VoucherController@counterSignVoucher')->name('vouchers.counter-sign');
    Route::put('vouchers/update-signatories/{id}', '\Revlv\Controllers\Biddings\VoucherController@updateSignatory')->name('vouchers.update-signatories');
    Route::get('vouchers/print/{id}', '\Revlv\Controllers\Biddings\VoucherController@viewPrint')->name('vouchers.print');
    Route::get('vouchers/logs/{id}', '\Revlv\Controllers\Biddings\VoucherController@viewLogs')->name('vouchers.logs');
    Route::post('vouchers/preaudit/{id}', '\Revlv\Controllers\Biddings\VoucherController@preauditVoucher')->name('vouchers.preaudit');
    Route::post('vouchers/certify/{id}', '\Revlv\Controllers\Biddings\VoucherController@certifyVoucher')->name('vouchers.certify');
    Route::post('vouchers/journal/{id}', '\Revlv\Controllers\Biddings\VoucherController@journalVoucher')->name('vouchers.journal');
    Route::post('vouchers/approved/{id}', '\Revlv\Controllers\Biddings\VoucherController@approvedVoucher')->name('vouchers.approved');
    Route::post('vouchers/released/{id}', '\Revlv\Controllers\Biddings\VoucherController@releasePayment')->name('vouchers.released');
    Route::post('vouchers/received/{id}', '\Revlv\Controllers\Biddings\VoucherController@receivePayment')->name('vouchers.received');
    Route::resource('vouchers', '\Revlv\Controllers\Biddings\VoucherController');
});

/*
|--------------------------------------------------------------------------
| Library Routes
|--------------------------------------------------------------------------
|
*/
Route::group(['as' => 'library.', 'prefix' => 'library'], function () {
    Route::get('supplier-files', '\Revlv\Controllers\Settings\SupplierController@files')->name('supplier-files.index');
    Route::get('unit-files', '\Revlv\Controllers\Settings\CateredUnitController@files')->name('unit-files.index');
    Route::resource('catalogs', '\Revlv\Controllers\Library\CatalogController');

    Route::get('file/download/{id}', '\Revlv\Controllers\Library\FileController@downloadFile')->name('file.download');
    Route::get('file/pending', '\Revlv\Controllers\Library\FileController@pending')->name('files.pending');
    Route::get('file/approved/{id}', '\Revlv\Controllers\Library\FileController@approved')->name('files.approved');
    Route::resource('files', '\Revlv\Controllers\Library\FileController');
});


Route::get('/item-price/update/{id}', '\Revlv\Controllers\Procurements\PurchaseOrderController@updateItemPrice')->name('item-price.update');
Route::put('line-item/update/{id}', '\Revlv\Controllers\Procurements\UPRController@updateLineItem')->name('line-item.update');

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
    Route::get('unit-purchase-requests/download-items/{id}', '\Revlv\Controllers\Procurements\UPRController@downloadItems')->name('unit-purchase-requests.download-items');


    Route::get('unit-purchase-requests/view-cancelled', '\Revlv\Controllers\Procurements\UPRController@viewCancelled')->name('unit-purchase-requests.view-cancel');
    Route::get('unit-purchase-requests/view-completed', '\Revlv\Controllers\Procurements\UPRController@viewCompleted')->name('unit-purchase-requests.view-completed');
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

    Route::get('unit-purchase-requests/imports2', '\Revlv\Controllers\Procurements\UPRController@uploadView2')->name('unit-purchase-requests.imports2');

    Route::post('unit-purchase-requests/import-file2', '\Revlv\Controllers\Procurements\UPRController@uploadFile2')->name('unit-purchase-requests.import-file2');
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
    Route::get('canvassing/print-landscape/{id}', '\Revlv\Controllers\Procurements\CanvassingController@viewPrintLandscape')->name('canvassing.print-landscape');
    Route::get('canvassing/cop/{id}', '\Revlv\Controllers\Procurements\CanvassingController@viewCOP')->name('canvassing.cop');
    Route::get('canvassing/rop/{id}', '\Revlv\Controllers\Procurements\CanvassingController@viewROP')->name('canvassing.rop');
    Route::get('canvassing/mom/{id}', '\Revlv\Controllers\Procurements\CanvassingController@viewMOM')->name('canvassing.mom');
    Route::post('canvassing/signatories/{id}', '\Revlv\Controllers\Procurements\CanvassingController@addSignatories')->name('canvassing.signatories');
    Route::post('canvassing/opening/{id}', '\Revlv\Controllers\Procurements\CanvassingController@openCanvass')->name('canvassing.opening');
    Route::post('canvassing/failed', '\Revlv\Controllers\Procurements\CanvassingController@failedCanvass')->name('canvassing.failed');
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
    Route::get('rfq-proponents/print/{id}', '\Revlv\Controllers\Procurements\RFQProponentController@viewPrint')->name('rfq-proponents.print');
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

    Route::put('noa/update-dates/{id}', '\Revlv\Controllers\Procurements\NoticeOfAwardController@updateDates')->name('noa.update-dates');
    Route::put('noa/philgeps/{id}', '\Revlv\Controllers\Procurements\NoticeOfAwardController@philgepsPosting')->name('noa.philgeps');
    Route::get('noa/logs/{id}', '\Revlv\Controllers\Procurements\NoticeOfAwardController@viewLogs')->name('noa.logs');
    Route::post('noa/accepted', '\Revlv\Controllers\Procurements\NoticeOfAwardController@acceptNOA')->name('noa.accepted');
    Route::put('noa/update-signatory/{id}', '\Revlv\Controllers\Procurements\NoticeOfAwardController@updateSignatory')->name('noa.update-signatory');
    Route::get('noa/print/{id}', '\Revlv\Controllers\Procurements\NoticeOfAwardController@viewPrint')->name('noa.print');
    Route::get('noa/print2/{id}', '\Revlv\Controllers\Procurements\NoticeOfAwardController@viewPrint2')->name('noa.print2');
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
    Route::get('purchase-orders/print2/{id}', '\Revlv\Controllers\Procurements\PurchaseOrderController@viewPrint2')->name('purchase-orders.print2');
    Route::get('purchase-orders/print3/{id}', '\Revlv\Controllers\Procurements\PurchaseOrderController@viewPrint3')->name('purchase-orders.print3');
    Route::get('purchase-orders/print-terms/{id}', '\Revlv\Controllers\Procurements\PurchaseOrderController@viewPrintTerms')->name('purchase-orders.print-terms');
    Route::get('purchase-orders/print-contract/{id}', '\Revlv\Controllers\Procurements\PurchaseOrderController@viewPrintContract')->name('purchase-orders.print-contract');
    Route::get('purchase-orders/print-coa/{id}', '\Revlv\Controllers\Procurements\PurchaseOrderController@viewPrintCOA')->name('purchase-orders.print-coa');
    Route::get('purchase-orders/rfq/{rfq_id}', '\Revlv\Controllers\Procurements\PurchaseOrderController@createFromRfq')->name('purchase-orders.rfq');
    Route::get('purchase-orders/coa-file/{id}', '\Revlv\Controllers\Procurements\PurchaseOrderController@downloadCoa')->name('purchase-orders.coa-file');
    Route::put('purchase-orders/update-dates/{id}', '\Revlv\Controllers\Procurements\PurchaseOrderController@updateDates')->name('purchase-orders.update-dates');
    Route::post('purchase-orders/import/{id}', '\Revlv\Controllers\Procurements\PurchaseOrderController@importPrice')->name('purchase-orders.import');
    Route::get('purchase-orders/import/{id}', '\Revlv\Controllers\Procurements\PurchaseOrderController@createFromRfqWithImport')->name('purchase-orders.view-import');
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
    Route::put('ntp/philgeps/{id}', '\Revlv\Controllers\Procurements\NoticeToProceedController@philgepsPosting')->name('ntp.philgeps');
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
    Route::get('delivery-orders/get-item-lists/{id}', '\Revlv\Controllers\Procurements\DeliveryController@GetAllItems')->name('delivery-orders.get-item-lists');

    Route::get('delivery-orders/get-item-orders/{id}/{item?}', '\Revlv\Controllers\Procurements\DeliveryController@GetItemOrders')->name('delivery-orders.get-item-orders');

    Route::get('delivery-orders/lists/{id}', '\Revlv\Controllers\Procurements\DeliveryController@listsAll')->name('delivery-orders.lists');


    Route::get('delivery-orders/store-by-dr/{id}', '\Revlv\Controllers\Procurements\DeliveredInspectionReportController@storeByDR')->name('delivery-orders.store-by-dr');
    Route::get('delivery-orders/edit-dates/{id}', '\Revlv\Controllers\Procurements\DeliveryController@editDates')->name('delivery-orders.edit-dates');
    Route::get('delivery-orders/logs/{id}', '\Revlv\Controllers\Procurements\DeliveryController@viewLogs')->name('delivery-orders.logs');
    Route::get('delivery-orders/print/{id}', '\Revlv\Controllers\Procurements\DeliveryController@viewPrint')->name('delivery-orders.print');
    Route::put('delivery-orders/update-dates/{id}', '\Revlv\Controllers\Procurements\DeliveryController@updateDates')->name('delivery-orders.update-dates');
    Route::put('delivery-orders/update-signatory/{id}', '\Revlv\Controllers\Procurements\DeliveryController@updateSignatory')->name('delivery-orders.update-signatory');
    Route::post('delivery-orders/create-purchase/{id}', '\Revlv\Controllers\Procurements\DeliveryController@createFromPurchase')->name('delivery-orders.create-purchase');
    Route::post('delivery-orders/completed/{id}', '\Revlv\Controllers\Procurements\DeliveryController@completeOrder')->name('delivery-orders.completed');


    Route::post('delivery-orders/attachments/{id}', '\Revlv\Controllers\Procurements\DeliveryController@uploadAttachment')->name('delivery-orders.attachments.store');

    Route::get('delivery-orders/download/{id}', '\Revlv\Controllers\Procurements\DeliveryController@downloadAttachment')->name('delivery-orders.attachments.download');

    Route::resource('delivery-orders', '\Revlv\Controllers\Procurements\DeliveryController');

    /*
    |--------------------------------------------------------------------------
    | Inspection Routes
    |--------------------------------------------------------------------------
    |
    */
    Route::get('inspection-and-acceptance/logs/{id}', '\Revlv\Controllers\Procurements\InspectionAndAcceptanceController@viewLogs')->name('inspection-and-acceptance.logs');
    Route::get('inspection-and-acceptance/print/{id}', '\Revlv\Controllers\Procurements\InspectionAndAcceptanceController@viewPrint')->name('inspection-and-acceptance.print');
    Route::get('inspection-and-acceptance/print-mfo2/{id}', '\Revlv\Controllers\Procurements\InspectionAndAcceptanceController@viewPrintMFO2')->name('inspection-and-acceptance.print-mfo2');
    Route::get('inspection-and-acceptance/print-mfo/{id}', '\Revlv\Controllers\Procurements\InspectionAndAcceptanceController@viewPrintMFO')->name('inspection-and-acceptance.print-mfo');
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
    Route::get('delivered-inspections/print-ris/{id}', '\Revlv\Controllers\Procurements\DeliveredInspectionReportController@viewPrintRIS')->name('delivered-inspections.print-ris');
    Route::get('delivered-inspections/print-ris-new/{id}', '\Revlv\Controllers\Procurements\DeliveredInspectionReportController@viewPrintRISNew')->name('delivered-inspections.print-ris-new');
    Route::get('delivered-inspections/print-ris-form-2/{id}', '\Revlv\Controllers\Procurements\DeliveredInspectionReportController@viewPrintRISForm2')->name('delivered-inspections.print-ris-form2');
    Route::get('delivered-inspections/print-ris2/{id}', '\Revlv\Controllers\Procurements\DeliveredInspectionReportController@viewPrintRIS2')->name('delivered-inspections.print-ris2');
    Route::get('delivered-inspections/print-ris2-form2/{id}', '\Revlv\Controllers\Procurements\DeliveredInspectionReportController@viewPrintRIS2Form2')->name('delivered-inspections.print-ris2-form2');
    Route::get('delivered-inspections/print-rsmi/{id}', '\Revlv\Controllers\Procurements\DeliveredInspectionReportController@viewPrintRSMI')->name('delivered-inspections.print-rsmi');
    Route::get('delivered-inspections/print-rsmi2/{id}', '\Revlv\Controllers\Procurements\DeliveredInspectionReportController@viewPrintRSMI2')->name('delivered-inspections.print-rsmi2');
    Route::get('delivered-inspections/print-rar/{id}', '\Revlv\Controllers\Procurements\DeliveredInspectionReportController@viewPrintRAR')->name('delivered-inspections.print-rar');
    Route::get('delivered-inspections/print-rar2/{id}', '\Revlv\Controllers\Procurements\DeliveredInspectionReportController@viewPrintRAR2')->name('delivered-inspections.print-rar2');
    Route::get('delivered-inspections/print-coi/{id}', '\Revlv\Controllers\Procurements\DeliveredInspectionReportController@viewPrintCOI')->name('delivered-inspections.print-coi');
    Route::get('delivered-inspections/print-coi2/{id}', '\Revlv\Controllers\Procurements\DeliveredInspectionReportController@viewPrintCOI2')->name('delivered-inspections.print-coi2');
    Route::get('delivered-inspections/print-diir2/{id}', '\Revlv\Controllers\Procurements\DeliveredInspectionReportController@viewPrintDIIR2')->name('delivered-inspections.print-diir2');
    Route::get('delivered-inspections/print-pre-repair/{id}', '\Revlv\Controllers\Procurements\DeliveredInspectionReportController@viewPrintPreRepair')->name('delivered-inspections.print-pre-repair');
    Route::get('delivered-inspections/print-post-repair/{id}', '\Revlv\Controllers\Procurements\DeliveredInspectionReportController@viewPrintPostRepair')->name('delivered-inspections.print-post-repair');
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
    Route::get('vouchers/print2/{id}', '\Revlv\Controllers\Procurements\VoucherController@viewPrint2')->name('vouchers.print2');
    Route::get('vouchers/print-wotax/{id}', '\Revlv\Controllers\Procurements\VoucherController@viewPrintNoTax')->name('vouchers.print-wotax');

    Route::get('vouchers/logs/{id}', '\Revlv\Controllers\Procurements\VoucherController@viewLogs')->name('vouchers.logs');
    Route::post('vouchers/prepare-payment/{id}', '\Revlv\Controllers\Procurements\VoucherController@preparePaymentVoucher')->name('vouchers.prepare-payment');
    Route::post('vouchers/counter-sign/{id}', '\Revlv\Controllers\Procurements\VoucherController@counterSignVoucher')->name('vouchers.counter-sign');
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
    Route::resource('forms', '\Revlv\Controllers\Settings\FormsController');
    Route::resource('forms-headers', '\Revlv\Controllers\Settings\HeaderController');
    Route::resource('forms-pcco-headers', '\Revlv\Controllers\Settings\PCCOHeaderController');
    Route::resource('forms-vouchers', '\Revlv\Controllers\Settings\VoucherFormController');
    Route::resource('forms-mfo', '\Revlv\Controllers\Settings\MFOFormController');
    Route::resource('forms-ris', '\Revlv\Controllers\Settings\RISFormController');
    Route::resource('forms-ris2', '\Revlv\Controllers\Settings\RIS2FormController');
    Route::resource('forms-rsmi', '\Revlv\Controllers\Settings\RSMIFormController');
    Route::resource('forms-rar', '\Revlv\Controllers\Settings\RARFormController');
    Route::resource('forms-coi', '\Revlv\Controllers\Settings\COIFormController');
    Route::resource('forms-po', '\Revlv\Controllers\Settings\POFormController');
    Route::resource('announcements', '\Revlv\Controllers\Settings\AnnouncementController');

    Route::get('catered-units/attachments/delete/{id}', '\Revlv\Controllers\Settings\CateredUnitController@removeAttachment')->name('catered-units.attachments.destroy');
    Route::post('catered-units/attachments/{id}', '\Revlv\Controllers\Settings\CateredUnitController@uploadAttachment')->name('catered-units.attachments.store');

    Route::get('catered-units/download/{id}', '\Revlv\Controllers\Settings\CateredUnitController@downloadAttachment')->name('catered-units.attachments.download');

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


    Route::get('suppliers/transactions/{id}', '\Revlv\Controllers\Settings\SupplierController@viewTransactions')->name('suppliers.transactions');
    Route::get('suppliers/print', '\Revlv\Controllers\Settings\SupplierController@printView')->name('suppliers.print');

    Route::get('suppliers/attachments/delete/{id}', '\Revlv\Controllers\Settings\SupplierController@deteleAttachment')->name('suppliers.attachments.destroy');

    Route::post('suppliers/attachments/{id}', '\Revlv\Controllers\Settings\SupplierController@uploadAttachment')->name('suppliers.attachments.store');

    Route::get('suppliers/download/{id}', '\Revlv\Controllers\Settings\SupplierController@downloadAttachment')->name('suppliers.attachments.download');

    Route::post('suppliers/accepts/{id}', '\Revlv\Controllers\Settings\SupplierController@acceptSupplier')->name('suppliers.accepts');
    Route::post('suppliers/blocked/{id}', '\Revlv\Controllers\Settings\SupplierController@blockedSupplier')->name('suppliers.blocked');
    Route::get('suppliers/un-blocked/{id}', '\Revlv\Controllers\Settings\SupplierController@unblockedSupplier')->name('suppliers.un-blocked');
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



Route::resource('notify', '\Revlv\Controllers\Notifications\NotifyController');
/*
|--------------------------------------------------------------------------
| Datatables Routes
|--------------------------------------------------------------------------
|
*/
Route::group(['as' => 'datatables.', 'prefix' => 'datatables'], function () {

    Route::get('notifications', '\Revlv\Controllers\Notifications\NotifyController@getDatatable')->name('notifications');
    Route::get('change-logs', '\Revlv\Controllers\Notifications\ChangeLogsController@getDatatable')->name('change-logs');
    Route::get('audit-logs', '\Revlv\Controllers\Settings\AuditLogController@getDatatable')->name('audit-logs');
    Route::get('noa-acceptance', '\Revlv\Controllers\Biddings\NOAController@getDatatable')->name('biddings.noa-acceptance');
    Route::get('catalogs', '\Revlv\Controllers\Library\CatalogController@getDatatable')->name('library.catalogs');
    Route::get('files', '\Revlv\Controllers\Library\FileController@getDatatable')->name('library.files');
    Route::get('pending-files', '\Revlv\Controllers\Library\FileController@getPendingDatatable')->name('library.pending-files');

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
    Route::get('supplier-files', '\Revlv\Controllers\Settings\SupplierController@getFilesDatatable')->name('settings.supplier-files');
    Route::get('unit-files', '\Revlv\Controllers\Settings\CateredUnitController@getFilesDatatable')->name('settings.unit-files');

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
    Route::get('psr-transactions', '\Revlv\Controllers\Reports\PSRController@getDatatable')->name('reports.psr-transactions');
    Route::get('unit-psr-transactions', '\Revlv\Controllers\Reports\UnitPSRController@getDatatable')->name('reports.unit-psr-transactions');
    Route::post('psr', '\Revlv\Controllers\Reports\PSRController@getPSRDatatable')->name('reports.psr');
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
    Route::get('forms_rfq', '\Revlv\Controllers\Settings\FormsController@getDatatable')->name('maintenance.forms.rfq');
    Route::get('forms_headers', '\Revlv\Controllers\Settings\HeaderController@getDatatable')->name('maintenance.forms.headers');
    Route::get('forms_pcco_headers', '\Revlv\Controllers\Settings\PCCOHeaderController@getDatatable')->name('maintenance.forms.pcco-headers');
    Route::get('forms-vouchers', '\Revlv\Controllers\Settings\VoucherFormController@getDatatable')->name('maintenance.forms.vouchers');
    Route::get('forms-mfo', '\Revlv\Controllers\Settings\MFOFormController@getDatatable')->name('maintenance.forms.mfo');
    Route::get('forms-ris', '\Revlv\Controllers\Settings\RISFormController@getDatatable')->name('maintenance.forms.ris');
    Route::get('forms-ris2', '\Revlv\Controllers\Settings\RIS2FormController@getDatatable')->name('maintenance.forms.ris2');
    Route::get('forms-rsmi', '\Revlv\Controllers\Settings\RSMIFormController@getDatatable')->name('maintenance.forms.rsmi');
    Route::get('forms-rar', '\Revlv\Controllers\Settings\RARFormController@getDatatable')->name('maintenance.forms.rar');
    Route::get('forms-coi', '\Revlv\Controllers\Settings\COIFormController@getDatatable')->name('maintenance.forms.coi');
    Route::get('forms-po', '\Revlv\Controllers\Settings\POFormController@getDatatable')->name('maintenance.forms.po');


    /*
    |--------------------------------------------------------------------------
    | Procurement Routes
    |--------------------------------------------------------------------------
    |
    */
    Route::get('procurements.unit-purchase-requests', '\Revlv\Controllers\Procurements\UPRController@getDatatable')->name('procurements.unit-purchase-request');
    Route::get('procurements/unit-purchase-requests/cancelled', '\Revlv\Controllers\Procurements\UPRController@getCancelledDatatable')->name('procurements.unit-purchase-request.cancelled');
    Route::get('procurements/unit-purchase-requests/completed', '\Revlv\Controllers\Procurements\UPRController@getCompletedDatatable')->name('procurements.unit-purchase-request.completed');
    Route::get('procurements/unit-purchase-requests/drafts', '\Revlv\Controllers\Procurements\UPRController@getDraftDatatable')->name('procurements.unit-purchase-request.drafts');
    Route::get('biddings/unit-purchase-requests/cancelled', '\Revlv\Controllers\Biddings\UPRController@getCancelledDatatable')->name('biddings.unit-purchase-request.cancelled');
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
    Route::get('delivery-orders-lists/{id}', '\Revlv\Controllers\Procurements\DeliveryController@getListDatatable')->name('procurements.delivery-orders-lists');
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
    Route::get('preproc', '\Revlv\Controllers\Biddings\PreProcController@getDatatable')->name('biddings.preproc');
    Route::get('itb', '\Revlv\Controllers\Biddings\InvitationToBidController@getDatatable')->name('biddings.itb');
    Route::get('philgeps', '\Revlv\Controllers\Biddings\PhilGepsController@getDatatable')->name('biddings.philgeps');
    Route::get('bid-docs', '\Revlv\Controllers\Biddings\BidDocsController@getDatatable')->name('biddings.bid-docs');
    Route::get('pre-bids', '\Revlv\Controllers\Biddings\PreBidController@getDatatable')->name('biddings.pre-bids');
    Route::get('bid-openings', '\Revlv\Controllers\Biddings\BidOpeningController@getDatatable')->name('biddings.bid-openings');
    Route::get('post-qualifications', '\Revlv\Controllers\Biddings\PostQualificationController@getDatatable')->name('biddings.post-qualifications');


    Route::get('noa-biddings', '\Revlv\Controllers\Biddings\NOAController@getDatatable')->name('biddings.noa');
    Route::get('purchase-orders-bidding', '\Revlv\Controllers\Biddings\PurchaseOrderController@getDatatable')->name('biddings.purchase-orders');
    Route::get('ntp-bidding', '\Revlv\Controllers\Biddings\NoticeToProceedController@getDatatable')->name('biddings.ntp');
    Route::get('delivery-orders-bidding', '\Revlv\Controllers\Biddings\DeliveryController@getDatatable')->name('biddings.delivery-orders');
    Route::get('inspection-and-acceptance-bidding', '\Revlv\Controllers\Biddings\InspectionAndAcceptanceController@getDatatable')->name('biddings.inspection-and-acceptance');

    Route::get('delivered-inspections-bidding', '\Revlv\Controllers\Biddings\DeliveredInspectionReportController@getDatatable')->name('biddings.delivered-inspections');
    Route::get('vouchers-bidding', '\Revlv\Controllers\Biddings\VoucherController@getDatatable')->name('biddings.vouchers');
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