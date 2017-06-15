<?php

namespace Revlv\Procurements\ProponentAttachments;

use Illuminate\Database\Eloquent\Model;

class ProponentAttachmentEloquent extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'proponents_attachments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'proponent_id',
        'name',
        'file_name',
        'user_id',
        'upload_date',
    ];

    /**
     * [users description]
     *
     * @return [type] [description]
     */
    public function users()
    {
        return $this->belongsTo('\App\User', 'user_id');
    }
}
