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

    protected $with  = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'message',
        'chat_id',
        'status',
        'seen_by',
        'is_seen'
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
