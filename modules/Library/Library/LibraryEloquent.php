<?php

namespace Revlv\Library\Library;

use Illuminate\Database\Eloquent\Model;

class LibraryEloquent extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'library';

    protected $casts = [
        'id' => 'string'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'catalog_id',
        'tags',
        'file_name',
        'status',
        'uploaded_by',
    ];

}
