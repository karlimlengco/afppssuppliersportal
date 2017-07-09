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

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
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
