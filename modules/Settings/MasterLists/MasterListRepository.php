<?php

namespace Revlv\Settings\MasterLists;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class MasterListRepository extends BaseRepository
{
    use  DatatableTrait;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return MasterListEloquent::class;
    }

    /**
     * [findByFilename description]
     *
     * @param  [type] $file [description]
     * @return [type]       [description]
     */
    public function findByFilename($file)
    {
        $model  =   $this->model;

        $model  =   $model->where('file_name', '=', $file);

        $model  =   $model->first();

        return $model;
    }
}
