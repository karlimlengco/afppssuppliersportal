<?php

namespace Revlv\Chats;

use Illuminate\Database\Eloquent\Model;

class ChatEloquent extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'chats';

    protected $with =   ['sender', 'receiver', 'lastMessage', 'messages'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sender_id',
        'title',
        'upr_id',
        'receiver_id'
    ];

    /**
     * [sender description]
     *
     * @return [type] [description]
     */
    public function  sender()
    {
        return $this->belongsTo('\App\User', 'sender_id');
    }

    /**
     * [receiver description]
     *
     * @return [type] [description]
     */
    public function  receiver()
    {
        return $this->belongsTo('\App\User', 'receiver_id');
    }

    /**
     * [lastMessage description]
     *
     * @return [type] [description]
     */
    public function  lastMessage()
    {
        return $this->hasOne('\Revlv\Chats\Message\MessageEloquent', 'chat_id');
    }

    /**
     * [messages description]
     *
     * @return [type] [description]
     */
    public function  messages()
    {
        return $this->hasMany('\Revlv\Chats\Message\MessageEloquent', 'chat_id');
    }

}
