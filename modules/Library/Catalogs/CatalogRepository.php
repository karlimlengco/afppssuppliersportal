<?php

namespace Revlv\Library\Catalogs;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class CatalogRepository extends BaseRepository
{
    use DatatableTrait;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return CatalogEloquent::class;
    }
}
