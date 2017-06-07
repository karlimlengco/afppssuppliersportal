<?php

namespace Revlv\Settings\AuditLogs;

use Illuminate\Database\Eloquent\Model;

class AuditLogEloquent extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'audits';


    /**
     * [user description]
     *
     * @return [type] [description]
     */
    public function user()
    {
        return $this->belongsTo('\App\User', 'user_id');
    }
}
