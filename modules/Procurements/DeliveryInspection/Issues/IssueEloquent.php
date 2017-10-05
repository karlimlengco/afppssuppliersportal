<?php

namespace Revlv\Procurements\DeliveryInspection\Issues;

use Illuminate\Database\Eloquent\Model;

class IssueEloquent extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'inspection_issues';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'inspection_id',
        'dr_id',
        'upr_id',
        'rfq_id',
        'rfq_number',
        'upr_number',
        'issue',
        'prepared_by',
        'remarks',
        'is_corrected',
    ];

    protected $casts = [
        'id' => 'string'
    ];

    public $incrementing = false;

    /**
     *  Setup model event hooks
     */
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            if($model->id == null)
            {
              $model->id = (string) \Uuid::generate();
            }
        });
    }

    /**
     * [prepared_by description]
     *
     * @return [type] [description]
     */
    public function prepared()
    {
        return $this->belongsTo('\App\User', 'prepared_by');
    }

    /**
     * [inspectionscription]
     *
     * @return [type] [description]
     */
    public function inspection()
    {
        return $this->belongsTo('\Revlv\Procurements\DeliveryInspection\DeliveryInspectionEloquent', 'inspection_id');
    }
}
