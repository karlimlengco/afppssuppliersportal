<?php

namespace Revlv\Settings\Signatories;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class SignatoryRepository extends BaseRepository
{
    use  DatatableTrait;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return SignatoryEloquent::class;
    }

    /**
     * Return the model by its key valued pair
     *
     * @param string $id
     * @param string $value
     * @return mixed
     */
    public function lists($id = 'id', $value = 'name')
    {
        $model =  $this->model;

        $model =  $model->select([
            \DB::raw("CONCAT(name, ' (', designation,')') AS name"),
            'id'
        ]);

        return $model->pluck($value, $id)->all();
    }
}
