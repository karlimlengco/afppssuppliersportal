<?php

namespace Revlv\Chats;

use Illuminate\Database\Eloquent\Model;

class ChatMemberEloquent extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'chat_members';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'chat_id',
        'user_id'
    ];

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
     * [sender description]
     *
     * @return [type] [description]
     */
    public function  user()
    {
        return $this->belongsTo('\App\User', 'user_id');
    }

    /**
     * [sender description]
     *
     * @return [type] [description]
     */
    public function  chat()
    {
        return $this->belongsTo('\Revlv\Chats\ChatEloquent', 'chat_id');
    }

}
