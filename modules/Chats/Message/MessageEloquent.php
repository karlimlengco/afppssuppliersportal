<?php

namespace Revlv\Chats\Message;

use Illuminate\Database\Eloquent\Model;

class MessageEloquent extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'messages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'message',
        'user_id',
    ];

    /**
     * [user description]
     *
     * @return [type] [description]
     */
    public function  user()
    {
        return $this->belongsTo('\App\User', 'user_id');
    }

}