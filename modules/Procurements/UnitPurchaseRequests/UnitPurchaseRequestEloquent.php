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
        'place_of_delivery',
        'procurement_office',
        'terminated_date',
        'procurement_type',
        'mode_of_procurement',
        'chargeability',
        'remarks',
        'update_remarks',
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
        'date_processed',
        'total_amount',
        'approver_text',
        'fund_signatory_text',
        'requestor_text',

        // 'requestor_id',
        // 'fund_signatory_id',
        // 'approver_id',

    ];


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'unit_purchase_requests';

    protected $with =   ['unit','centers'];

    protected $dates = [
        'created_at',
        'updated_at',
        'date_prepared',
        'completed_at',
        'cancelled_at',
        'date_processed',
        'terminated_date',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'place_of_delivery',
        'procurement_office',
        'mode_of_procurement',
        'chargeability',
        'procurement_type',
        'total_amount',

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

        'completed_at',

        'cancelled_at',
        'cancel_reason',

        'remarks',
        'update_remarks',

        'date_processed',
        'processed_by',

        'terminated_date',
        'terminate_status',
        'terminated_by',

        'requestor_id',
        'requestor_text',
        'fund_signatory_text',
        'approver_text',
        'fund_signatory_id',
        'approver_id',

        'status',
        'state',

        'days',
        'delay_count',

        'calendar_days',
        'last_remarks',
        'last_action',


        'next_allowable',
        'next_due',
        'next_step',
    ];

    /**
     * [itb description]
     *
     * @return [type] [description]
     */
    public function itb()
    {
        return $this->hasOne('\Revlv\Biddings\InvitationToBid\InvitationToBidEloquent',  'upr_id')->orderBy('created_at', 'desc');
    }

    /**
     * [itb description]
     *
     * @return [type] [description]
     */
    public function itbs()
    {
        return $this->hasMany('\Revlv\Biddings\InvitationToBid\InvitationToBidEloquent',  'upr_id');
    }

    /**
     * [document_accept description]
     *
     * @return [type] [description]
     */
    public function document_accept()
    {
        return $this->hasOne('\Revlv\Biddings\DocumentAcceptance\DocumentAcceptanceEloquent',  'upr_id')->whereNotNull('approved_date')->orderBy('created_at', 'desc');
    }

    /**
     * [document_accept description]
     *
     * @return [type] [description]
     */
    public function preproc()
    {
        return $this->hasOne('\Revlv\Biddings\PreProc\PreProcEloquent',  'upr_id')->whereNull('resched_date')->orderBy('created_at', 'desc');
    }

    /**
     * [document_accept description]
     *
     * @return [type] [description]
     */
    public function preprocs()
    {
        return $this->hasMany('\Revlv\Biddings\PreProc\PreProcEloquent',  'upr_id');
    }

    /**
     * [document_accept description]
     *
     * @return [type] [description]
     */
    public function documents()
    {
        return $this->hasMany('\Revlv\Biddings\DocumentAcceptance\DocumentAcceptanceEloquent',  'upr_id');
    }

    /**
     * [document_accept description]
     *
     * @return [type] [description]
     */
    public function bid_conference()
    {
        return $this->hasOne('\Revlv\Biddings\PreBid\PreBidEloquent',  'upr_id')->whereNotNull('bid_opening_date')->orderBy('created_at', 'desc');
    }

    /**
     * [document_accept description]
     *
     * @return [type] [description]
     */
    public function bid_conferences()
    {
        return $this->hasMany('\Revlv\Biddings\PreBid\PreBidEloquent',  'upr_id');
    }

    /**
     * [document_accept description]
     *
     * @return [type] [description]
     */
    public function bid_issuance()
    {
        return $this->hasOne('\Revlv\Biddings\BidDocs\BidDocsEloquent',  'upr_id')->orderBy('created_at', 'desc');
    }

    /**
     * [bid_issuances description]
     *
     * @return [type] [description]
     */
    public function bid_issuances()
    {
        return $this->hasMany('\Revlv\Biddings\BidDocs\BidDocsEloquent',  'upr_id');
    }

    /**
     * [post_qual description]
     *
     * @return [type] [description]
     */
    public function bid_open()
    {
        return $this->hasOne('\Revlv\Biddings\BidOpening\BidOpeningEloquent',  'upr_id')->orderBy('created_at', 'desc');
    }

    /**
     * [post_qual description]
     *
     * @return [type] [description]
     */
    public function bid_opens()
    {
        return $this->hasMany('\Revlv\Biddings\BidOpening\BidOpeningEloquent',  'upr_id');
    }

    /**
     * [post_qual description]
     *
     * @return [type] [description]
     */
    public function post_qual()
    {
        return $this->hasOne('\Revlv\Biddings\PostQualification\PostQualificationEloquent',  'upr_id')->orderBy('created_at', 'desc');
    }

    /**
     * [post_qual description]
     *
     * @return [type] [description]
     */
    public function post_quals()
    {
        return $this->hasMany('\Revlv\Biddings\PostQualification\PostQualificationEloquent',  'upr_id')->orderBy('created_at', 'desc');
    }

    /**
     * [document_accept description]
     *
     * @return [type] [description]
     */
    public function bid_proponents()
    {
        return $this->hasMany('\Revlv\Biddings\BidDocs\BidDocsEloquent',  'upr_id')->orderByRaw("CAST(bid_amount as UNSIGNED)");
    }

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
     * [philgeps description]
     *
     * @return [type] [description]
     */
    public function philgeps_many()
    {
        return $this->hasMany('\Revlv\Procurements\PhilGepsPosting\PhilGepsPostingEloquent', 'upr_id');
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
        return $this->hasOne('\Revlv\Procurements\DeliveryOrder\DeliveryOrderEloquent',  'upr_id')->orderBy('created_at', 'desc');
    }

    /**
     * [delivery_orders description]
     *
     * @return [type] [description]
     */
    public function delivery_orders()
    {
        return $this->hasMany('\Revlv\Procurements\DeliveryOrder\DeliveryOrderEloquent',  'upr_id')->orderBy('created_at', 'desc');
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
     * [inspections description]
     *
     * @return [type] [description]
     */
    public function inspections()
    {
        return $this->hasOne('\Revlv\Procurements\InspectionAndAcceptance\InspectionAndAcceptanceEloquent', 'upr_id');
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
     * [types description]
     *
     * @return [type] [description]
     */
    public function types()
    {
        return $this->belongsTo('\Revlv\Settings\ProcurementTypes\ProcurementTypeEloquent', 'procurement_type');
    }

    /**
     * [centers description]
     *
     * @return [type] [description]
     */
    public function centers()
    {
        return $this->belongsTo('\Revlv\Settings\ProcurementCenters\ProcurementCenterEloquent', 'procurement_office');
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

    // /**
    //  * [accounts description]
    //  *
    //  * @return [type] [description]
    //  */
    // public function accounts()
    // {
    //     return $this->belongsTo('\Revlv\Settings\AccountCodes\AccountCodeEloquent', 'new_account_code');
    // }

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
