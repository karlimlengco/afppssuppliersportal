<?php

namespace Revlv\Procurements\Canvassing\Signatories;

use Illuminate\Database\Eloquent\Model;

class SignatoryEloquent extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'canvass_signatories';
    protected $with = 'signatory';


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
        'signatory_id',
        'is_present',
        'cop',
        'rop',
    ];

    /**
     * [opens description]
     *
     * @return [type] [description]
     */
    public function opens()
    {
        return $this->belongsTo('\Revlv\Procurements\Canvassing\CanvassingEloquent', 'canvass_id');
    }

    public function signatory()
    {
        return $this->belongsTo('\Revlv\Settings\Signatories\SignatoryEloquent', 'signatory_id');
    }
}
