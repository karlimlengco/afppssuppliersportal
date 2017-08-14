<?php

namespace Revlv\Settings\Suppliers\Attachments;

use Illuminate\Database\Eloquent\Model;

class AttachmentEloquent extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'supplier_attachments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'supplier_id',
        'name',
        'file_name',
        'user_id',
        'validity_date',
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