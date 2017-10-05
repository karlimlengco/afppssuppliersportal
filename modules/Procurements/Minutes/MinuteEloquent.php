<?php

namespace Revlv\Procurements\Minutes;

use Illuminate\Database\Eloquent\Model;

class MinuteEloquent extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'meeting_minutes';

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
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'date_opened',
        'time_opened',
        'venue',
        'officer_id',
        'time_closed',
        'prepared_by',
    ];

    /**
     * [members description]
     *
     * @return [type] [description]
     */
    public function members()
    {
        return $this->hasMany('\Revlv\Procurements\Minutes\Members\MemberEloquent', 'meeting_id');
    }

    /**
     * [officer description]
     *
     * @return [type] [description]
     */
    public function officer()
    {
        return $this->belongsTo('\Revlv\Settings\Signatories\SignatoryEloquent', 'officer_id');
    }

    /**
     * [members description]
     *
     * @return [type] [description]
     */
    public function canvass()
    {
        return $this->hasMany('\Revlv\Procurements\Minutes\Canvass\CanvassEloquent', 'meeting_id');
    }

}
