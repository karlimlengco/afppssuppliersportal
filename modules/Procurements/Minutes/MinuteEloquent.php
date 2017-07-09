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

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'date_opened',
        'time_opened',
        'venue',
        'time_closed',
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
     * [members description]
     *
     * @return [type] [description]
     */
    public function canvass()
    {
        return $this->hasMany('\Revlv\Procurements\Minutes\Canvass\CanvassEloquent', 'meeting_id');
    }

}
