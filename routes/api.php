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

Route::resource('catered-units', 'Maintenance\CateredUnitController');
Route::resource('account-codes', 'Maintenance\AccountController');
Route::resource('form-headers', 'Maintenance\FormHeaderController');
Route::resource('form-rfq', 'Maintenance\FormRFQController');
Route::resource('pcco-headers', 'Maintenance\PCCOHeaderController');
Route::resource('signatories', 'Maintenance\SignatoryController');
Route::resource('procurement-centers', 'Maintenance\PCCOController');
Route::resource('procurement-types', 'Maintenance\TypesController');
Route::resource('mode-of-procurements', 'Maintenance\ModesController');
Route::resource('chargeability', 'Maintenance\ChargeabilityController');
Route::resource('payment-terms', 'Maintenance\TermsController');
Route::resource('suppliers', 'Maintenance\SupplierController');
Route::resource('suppliers-attachments', 'Maintenance\SupplierAttachmentsController');
Route::resource('banks', 'Maintenance\BankController');

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

        Route::get('/user-roles-lists', [
            'uses' => 'UserController@userRole',
        ]);

        Route::resource('coi-forms', 'Maintenance\COIFormController');
        Route::resource('mfo-forms', 'Maintenance\MFOFormController');
        Route::resource('po-forms', 'Maintenance\POFormController');
        Route::resource('rar-forms', 'Maintenance\RARFormController');
        Route::resource('ris-forms', 'Maintenance\RISFormController');
        Route::resource('ris2-forms', 'Maintenance\RIS2FormController');
        Route::resource('rsmi-forms', 'Maintenance\RSMIFormController');
        Route::resource('voucher-forms', 'Maintenance\VoucherFormController');

        Route::resource('permissions', 'Maintenance\PermissionController');
        Route::resource('roles', 'Maintenance\RoleController');
        Route::resource('bacsec', 'Maintenance\BacSecController');
        Route::resource('unit-purchase-requests', 'Procurement\UPRController');
        Route::resource('upr-items', 'Procurement\UPRItemController');
        Route::resource('ispq', 'Procurement\ISPQController');
        Route::resource('philgeps', 'Procurement\PhilgepsController');
        Route::resource('rfq', 'Procurement\RFQController');
        Route::resource('rfq-proponents', 'Procurement\RFQProponentController');
        Route::resource('canvassing', 'Procurement\CanvassingController');
        Route::resource('noa', 'Procurement\NOAController');
        Route::resource('ntp', 'Procurement\NTPController');
        Route::resource('nod', 'Procurement\NODController');
        Route::resource('iaar', 'Procurement\IAARController');
        Route::resource('nod-items', 'Procurement\NODItemController');
        Route::resource('purchase-orders', 'Procurement\POController');
        Route::resource('purchase-orders-item', 'Procurement\POItemController');
        Route::resource('iaar-invoice', 'Procurement\IAARInvoiceController');
        Route::resource('diir', 'Procurement\DIIRController');
        Route::resource('diir-issue', 'Procurement\DIIRIssueController');
        Route::resource('vouchers', 'Procurement\VoucherController');

        Route::resource('document-acceptance', 'Procurement\DocumentAcceptanceController');
        Route::resource('preproc', 'Procurement\PreprocController');
        Route::resource('itb', 'Procurement\ITBController');
        Route::resource('bid-docs', 'Procurement\BidsController');
        Route::resource('pre-bid', 'Procurement\PrebidController');
        Route::resource('sobe', 'Procurement\SOBEController');
        Route::resource('post-qual', 'Procurement\PostController');
        Route::resource('invitations', 'Procurement\InvitationController');
    });

});