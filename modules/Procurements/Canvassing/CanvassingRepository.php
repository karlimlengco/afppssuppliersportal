<?php

namespace Revlv\Procurements\Canvassing;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class CanvassingRepository extends BaseRepository
{
    use  DatatableTrait, NOATrait;
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return CanvassingEloquent::class;
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
     * [listCompleted description]
     *
     * @return [type] [description]
     */
    public function listCompleted($id = 'id', $value = 'name')
    {
        $model  =   $this->model;

        $model  =   $model->select([
                'canvassing.*',
                'notice_of_awards.id as noa'
            ]);

        $model  =   $model->leftJoin('notice_of_awards', 'notice_of_awards.canvass_id', '=', 'canvassing.id');

        $model  =   $model->whereNotNull('notice_of_awards.id');
        $model  =   $model->pluck($value, $id)->all();


        return $model;

    }

}
