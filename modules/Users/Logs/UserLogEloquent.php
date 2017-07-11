<?php

namespace Revlv\Users\Logs;

use Illuminate\Database\Eloquent\Model;

class UserLogEloquent extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'audit_id',
        'admin_id',
        'is_viewed',
    ];

}
