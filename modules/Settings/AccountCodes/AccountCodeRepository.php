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
    public function findByName($name)
    {
        $model  =   $this->model;

        $model  =   $model->where('new_account_code', 'LIKE', "%$name%");

        return $model->first();
    }

    /**
     * [listCodes description]
     *
     *
     * @return [type] [description]
     */
    public function listOld()
    {
        $model  =   $this->model;

        $model  =   $model->select(['id', 'old_account_code']);

        $model  =   $model->where('old_account_code', '<>', "");

        return $model->pluck('old_account_code', 'id')->all();

    }

    /**
     * [listCodes description]
     *
     *
     * @return [type] [description]
     */
    public function lists($id = 'id', $value = 'name')
    {
        $model  =   $this->model;

        $model  =   $model->select([
            'id',
            \DB::raw("CONCAT(new_account_code,' (', old_account_code,')') AS new_account_code")
            ]);

        return $model->pluck('new_account_code', 'id')->all();

    }
}
