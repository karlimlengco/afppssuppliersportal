<?php

namespace Revlv\Users;

use Illuminate\Database\Eloquent\Model;

class RoleUserEloquent extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'role_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'role_id', 'user_id'];
}