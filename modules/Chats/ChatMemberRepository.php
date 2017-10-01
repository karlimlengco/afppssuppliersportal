<?php

namespace Revlv\Chats;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class ChatMemberRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ChatMemberEloquent::class;
    }

}
