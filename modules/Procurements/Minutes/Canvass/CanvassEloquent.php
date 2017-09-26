<?php

namespace Revlv\Procurements\Minutes\Canvass;

use Illuminate\Database\Eloquent\Model;

class CanvassEloquent extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'meeting_canvass';

    protected $with = 'canvass';

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
            $model->id = (string) \Uuid::generate();
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'meeting_id',
        'canvass_id',
    ];

    /**
     * [members description]
     *
     * @return [type] [description]
     */
    public function meeting()
    {
        return $this->belongsTo('\Revlv\Settings\Minutes\MinuteEloquent', 'meeting_id');
    }

    /**
     * [members description]
     *
     * @return [type] [description]
     */
    public function canvass()
    {
        return $this->belongsTo('\Revlv\Procurements\Canvassing\CanvassingEloquent', 'canvass_id');
    }

}
