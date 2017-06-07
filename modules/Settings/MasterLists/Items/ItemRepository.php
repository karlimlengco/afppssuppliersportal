<?php

namespace Revlv\Settings\MasterLists\Items;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class ItemRepository extends BaseRepository
{
    use  DatatableTrait;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ItemEloquent::class;
    }

    /**
     * [findByArticle description]
     *
     * @param  [type] $file [description]
     * @return [type]       [description]
     */
    public function findByArticle($article)
    {
        $model  =   $this->model;

        $model  =   $model->where('article', '=', $article);

        $model  =   $model->first();

        return $model;
    }

    /**
     * [listByMasterList description]
     *
     * @param  [type] $master [description]
     * @return [type]         [description]
     */
    public function listByMasterList($master)
    {
        $model  =   $this->model;

        $model  =   $model->where('master_id', '=', $master);

        return $model->pluck('article', 'id')->all();
    }
}
