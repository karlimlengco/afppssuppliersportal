<?php

namespace Revlv\Procurements\PhilGepsPosting\Attachments;

use Illuminate\Database\Eloquent\Model;

class AttachmentEloquent extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'philgeps_attachments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'philgeps_id',
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
