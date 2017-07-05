<?php

namespace Revlv\Procurements\UnitPurchaseRequests;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class UnitPurchaseRequestEloquent extends Model implements  AuditableContract
{
    use Auditable;

    /**
     * Attributes to include in the Audit.
     *
     * @var array
     */
    protected $auditInclude = [
        'date_prepared',
        'other_infos',
        'purpose',
        'update_remarks',
        'account_code',
        'completed_at',
    ];


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'unit_purchase_requests';

    protected $with =   ['unit','centers'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'place_of_delivery',
        'terminated_date',
        'terminated_by',
        'mode_of_procurement',
        'chargeability',
        'terminate_status',
        'remarks',
        'update_remarks',
        'days',
        'completed_at',
        'account_code',

        'fund_validity',
        'terms_of_payment',
        'other_infos',

        'units',
        'purpose',

        'project_name',
        'upr_number',
        'ref_number',

        'date_prepared',
        'prepared_by',

        'date_processed',
        'processed_by',

        'total_amount',

        'status',
        'state',

        'requestor_id',
        'fund_signatory_id',
        'approver_id',
    ];


    /**
     * [approver description]
     *
     * @return [type] [description]
     */
    public function approver()
    {
        return $this->belongsTo('\Revlv\Settings\Signatories\SignatoryEloquent', 'approver_id');
    }

    /**
     * [funder description]
     *
     * @return [type] [description]
     */
    public function funders()
    {
        return $this->belongsTo('\Revlv\Settings\Signatories\SignatoryEloquent', 'fund_signatory_id');
    }

    /**
     * [requestor description]
     *
     * @return [type] [description]
     */
    public function requestor()
    {
        return $this->belongsTo('\Revlv\Settings\Signatories\SignatoryEloquent', 'requestor_id');
    }

    /**
     * [philgeps description]
     *
     * @return [type] [description]
     */
    public function philgeps()
    {
        return $this->hasOne('\Revlv\Procurements\PhilGepsPosting\PhilGepsPostingEloquent', 'upr_id');
    }

    /**
     * [rfq description]
     *
     * @return [type] [description]
     */
    public function rfq()
    {
        return $this->hasOne('\Revlv\Procurements\BlankRequestForQuotation\BlankRFQEloquent', 'upr_id');
    }

    /**
     * [invitations description]
     *
     * @return [type] [description]
     */
    public function invitations()
    {
        return $this->hasOne('\Revlv\Procurements\InvitationToSubmitQuotation\Quotations\QuotationEloquent',  'upr_id');
    }

    /**
     * [canvassing description]
     *
     * @return [type] [description]
     */
    public function canvassing()
    {
        return $this->hasOne('\Revlv\Procurements\Canvassing\CanvassingEloquent', 'upr_id');
    }

    /**
     * [noa description]
     *
     * @return [type] [description]
     */
    public function noa()
    {
        return $this->hasOne('\Revlv\Procurements\NoticeOfAward\NOAEloquent',  'upr_id');
    }

    /**
     * [ntp description]
     *
     * @return [type] [description]
     */
    public function ntp()
    {
        return $this->hasOne('\Revlv\Procurements\NoticeToProceed\NTPEloquent',  'upr_id');
    }

    /**
     * [purchase_order description]
     *
     * @return [type] [description]
     */
    public function purchase_order()
    {
        return $this->hasOne('\Revlv\Procurements\PurchaseOrder\POEloquent','upr_id');
    }

    /**
     * [delivery_order description]
     *
     * @return [type] [description]
     */
    public function delivery_order()
    {
        return $this->hasOne('\Revlv\Procurements\DeliveryOrder\DeliveryOrderEloquent',  'upr_id');
    }

    /**
     * [delivery_order description]
     *
     * @return [type] [description]
     */
    public function diir()
    {
        return $this->hasOne('\Revlv\Procurements\DeliveryInspection\DeliveryInspectionEloquent',  'upr_id');
    }

    /**
     * [voucher description]
     *
     * @return [type] [description]
     */
    public function voucher()
    {
        return $this->hasOne('\Revlv\Procurements\Vouchers\VoucherEloquent',  'upr_id');
    }

    /**
     * [centers description]
     *
     * @return [type] [description]
     */
    public function centers()
    {
        return $this->belongsTo('\Revlv\Settings\ProcurementCenters\ProcurementCenterEloquent', 'place_of_delivery');
    }

    /**
     * [modes description]
     *
     * @return [type] [description]
     */
    public function modes()
    {
        return $this->belongsTo('\Revlv\Settings\ModeOfProcurements\ModeOfProcurementEloquent', 'mode_of_procurement');
    }

    /**
     * [charges description]
     *
     * @return [type] [description]
     */
    public function charges()
    {
        return $this->belongsTo('\Revlv\Settings\Chargeability\ChargeabilityEloquent', 'chargeability');
    }

    /**
     * [unit description]
     *
     * @return [type] [description]
     */
    public function unit()
    {
        return $this->belongsTo('\Revlv\Settings\CateredUnits\CateredUnitEloquent', 'units');
    }

    /**
     * [accounts description]
     *
     * @return [type] [description]
     */
    public function accounts()
    {
        return $this->belongsTo('\Revlv\Settings\AccountCodes\AccountCodeEloquent', 'account_code');
    }

    /**
     * [terms description]
     *
     * @return [type] [description]
     */
    public function terms()
    {
        return $this->belongsTo('\Revlv\Settings\PaymentTerms\PaymentTermEloquent', 'terms_of_payment');
    }

    /**
     * [users description]
     *
     * @return [type] [description]
     */
    public function users()
    {
        return $this->belongsTo('\App\User', 'prepared_by');
    }

    /**
     * [terminator description]
     *
     * @return [type] [description]
     */
    public function terminator()
    {
        return $this->belongsTo('\App\User', 'terminated_by');
    }

    /**
     * [processor description]
     *
     * @return [type] [description]
     */
    public function processor()
    {
        return $this->belongsTo('\App\User', 'processed_by');
    }

    /**
     * [items description]
     *
     * @return [type] [description]
     */
    public function items()
    {
        return $this->hasMany('\Revlv\Procurements\Items\ItemEloquent', 'upr_id');
    }

    /**
     * [attachments description]
     *
     * @return [type] [description]
     */
    public function attachments()
    {
         return $this->hasMany('\Revlv\Procurements\UnitPurchaseRequests\Attachments\AttachmentEloquent', 'upr_id');
    }

}
