<?php

namespace Revlv\Procurements\Canvassing\Signatories;

use Illuminate\Database\Eloquent\Model;

class SignatoryEloquent extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'canvass_signatories';
    protected $with = 'signatory';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'canvass_id',
        'signatory_id',
    ];

    /**
     * [opens description]
     *
     * @return [type] [description]
     */
    public function opens()
    {
        return $this->belongsTo('\Revlv\Procurements\Canvassing\CanvassingEloquent', 'canvass_id');
    }

    public function signatory()
    {
        return $this->belongsTo('\Revlv\Settings\Signatories\SignatoryEloquent', 'signatory_id');
    }
}
