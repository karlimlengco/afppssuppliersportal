<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cartalyst\Sentinel\Permissions\StandardPermissions;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Permission extends StandardPermissions implements AuditableContract
{
    use Auditable;

    /**
     * Attributes to include in the Audit.
     *
     * @var array
     */
    protected $auditInclude = [
        'permission',
        'description'
    ];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'permissions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['permission', 'description'];
}