<?php

namespace Revlv\Settings\AccountCodes;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class AccountCodeRepository extends BaseRepository
{
    use  DatatableTrait;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return AccountCodeEloquent::class;
    }

    /**
     * [findByName description]
     *
     * @param  [type] $name [description]
     * @return [type]       [description]
     */
    public function findByName($name)
    {
        $model  =   $this->model;

        $model  =   $model->where('new_account_code', 'LIKE', "%$name%");

        return $model->first();
    }
}
