<?php

namespace Revlv\Library\Library;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class LibraryRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return LibraryEloquent::class;
    }
}
