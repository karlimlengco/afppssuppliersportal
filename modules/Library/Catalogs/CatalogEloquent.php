<?php

namespace Revlv\Library\Catalogs;

use Illuminate\Database\Eloquent\Model;

class CatalogEloquent extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'library_catalogs';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
    ];

}
