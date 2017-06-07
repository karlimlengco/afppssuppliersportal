<?php

namespace Revlv\Settings\MasterLists;

use Illuminate\Database\Eloquent\Model;

class MasterListEloquent extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'master_lists';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'file_name',
        'user_id'
    ];

}
