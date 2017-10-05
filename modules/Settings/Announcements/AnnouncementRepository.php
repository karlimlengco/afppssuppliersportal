<?php

namespace Revlv\Settings\Announcements;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class AnnouncementRepository extends BaseRepository
{
    use  DatatableTrait;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return AnnouncementEloquent::class;
    }

    /**
     * [findByRFQId description]
     *
     * @param  [type] $rfq [description]
     * @return [type]      [description]
     */
    public function getById($id)
    {
        $model  =    $this->model;

        $model  =   $model->where('id', '=', $id);

        return $model->first();
    }

    /**
     * [findByName description]
     *
     * @param  [type] $name [description]
     * @return [type]       [description]
     */
    public function findByTitle($title)
    {
        $model  =   $this->model;

        $model  =   $model->where('title', 'LIKE', "%$title%");

        return $model->first();
    }
}
