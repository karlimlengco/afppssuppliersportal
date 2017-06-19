<?php

namespace Revlv\Procurements\DeliveryInspection;

use Illuminate\Database\Eloquent\Model;

class DeliveryInspectionEloquent extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'delivery_inspection';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dr_id',
        'upr_id',
        'rfq_id',
        'rfq_number',
        'upr_number',
        'delivery_number',
        'inspection_number',
        'start_date',
        'status',
        'closed_date',
        'started_by',
        'closed_by',
    ];

    /**
     * [started description]
     *
     * @return [type] [description]
     */
    public function started()
    {
        return $this->belongsTo('\App\User', 'started_by');
    }

    /**
     * [closed description]
     *
     * @return [type] [description]
     */
    public function closed()
    {
        return $this->belongsTo('\App\User', 'closed_by');
    }

    /**
     * [items description]
     *
     * @return [type] [description]
     */
    public function issues()
    {
        return $this->hasMany('\Revlv\Procurements\DeliveryInspection\Issues\IssueEloquent', 'inspection_id');
    }
}
