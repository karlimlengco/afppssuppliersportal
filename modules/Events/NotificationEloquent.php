<?php

namespace Revlv\Events;

use Illuminate\Database\Eloquent\Model;

class NotificationEloquent extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'notifications';

    protected $casts = [
        'id' => 'string'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'user_id',
        'event',
        'model',
        'model_id',
        'is_seen',
    ];

    /**
     * [receiver description]
     *
     * @return [type] [description]
     */
    public function  receiver()
    {
        return $this->belongsTo('\App\User', 'user_id');
    }

}
