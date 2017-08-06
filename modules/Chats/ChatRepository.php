<?php

namespace Revlv\Chats;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class ChatRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ChatEloquent::class;
    }


    /**
     * [findBySender description]
     *
     * @return [type] [description]
     */
    public function findBySender($sender)
    {
        $model  =   $this->model;

        $model  =   $model->where('sender_id', '=', $sender);

        return $model->first();
    }

    /**
     * [getAllAdmin description]
     *
     * @return [type] [description]
     */
    public function getAllAdmin($pagintate = 20)
    {
        $model  =   $this->model;

        $model  =   $model->whereNull('receiver_id');

        return $model->paginate($pagintate);
    }
}
