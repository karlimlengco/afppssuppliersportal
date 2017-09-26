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
        return $this->hasOne('\Revlv\Chats\Message\MessageEloquent', 'chat_id')->orderBy('created_at', 'desc');
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
