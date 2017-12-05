<?php

namespace Revlv\Settings\BacSec;

use Illuminate\Database\Eloquent\Model;

class BacSecEloquent extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'bacsec';

    protected $casts = [
        'id' => 'string'
    ];

    public $incrementing = false;

    /**
     *  Setup model event hooks
     */
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            if($model->id == null)
            {
              $model->id = (string) \Uuid::generate();
            }
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'pcco_id',
        'description',
    ];

}
