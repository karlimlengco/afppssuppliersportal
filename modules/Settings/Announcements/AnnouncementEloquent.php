<?php

namespace Revlv\Settings\Announcements;

use Illuminate\Database\Eloquent\Model;

class AnnouncementEloquent extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'announcements';

    protected $casts = [
        'id' => 'string'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'title',
        'message',
        'post_at',
        'expire_at',
        'status',
    ];

}
