<?php

namespace Revlv\Settings\MasterLists\Items;

use Illuminate\Database\Eloquent\Model;

class ItemEloquent extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'master_items';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'master_id',
        'article',
        'items',
    ];

}
