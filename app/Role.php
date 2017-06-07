<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cartalyst\Sentinel\Roles\EloquentRole;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Role extends EloquentRole implements AuditableContract
{
    use Auditable;

    /**
     * Attributes to include in the Audit.
     *
     * @var array
     */
    protected $auditInclude = [
        'name',
        'slug',
        'permissions',
    ];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'roles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug', 'permissions'];

    /**
    //  * The Users relationship.
    //  *
    //  * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
    //  */
    // public function users()
    // {
    //     $tenant = \App::make('config')->get('database.connections.tenant.database');
    //     return $this->belongsToMany(static::$usersModel, "$tenant.role_users", 'role_id', 'user_id')->withTimestamps();
    // }
}