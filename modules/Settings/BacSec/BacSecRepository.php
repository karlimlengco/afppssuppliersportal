<?php

namespace Revlv\Settings\BacSec;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class BacSecRepository extends BaseRepository
{
    use  DatatableTrait;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return BacSecEloquent::class;
    }

}
