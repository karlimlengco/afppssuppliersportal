<?php

namespace Revlv\Procurements\PhilGepsPosting;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class PhilGepsPostingEloquent extends Model implements  AuditableContract
{

    use Auditable;

    /**
     * Attributes to include in the Audit.
     *
     * @var array
     */
    protected $auditInclude = [
        'philgeps_number',
        'philgeps_posting',
        'deadline_rfq',
        'opening_time',
        'transaction_date',
        'update_remarks',
        'remarks',
    ];


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'philgeps_posting';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rfq_id',
        'upr_id',
        'philgeps_number',
        'rfq_number',
        'upr_number',
        'transaction_date',
        'philgeps_posting',
        'deadline_rfq',
        'update_remarks',
        'remarks',
        'days',
        'opening_time',
    ];

    /**
     * [attachments description]
     *
     * @return [type] [description]
     */
    public function attachments()
    {
         return $this->hasMany('\Revlv\Procurements\PhilGepsPosting\Attachments\AttachmentEloquent', 'philgeps_id');
    }
}
