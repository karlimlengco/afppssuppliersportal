<?php

namespace Revlv\Procurements\DeliveryOrder\Attachments;

use Illuminate\Database\Eloquent\Model;

class AttachmentEloquent extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'nod_attachments';

    protected $casts = [
        'id' => 'string'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'string',
        'nod_id',
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
