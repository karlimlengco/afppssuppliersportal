<?php

namespace Revlv\Procurements\Minutes\Members;

use Illuminate\Database\Eloquent\Model;

class MemberEloquent extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'meeting_members';

    protected $with = 'signatory';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'meeting_id',
        'signatory_id',
    ];

    /**
     * [members description]
     *
     * @return [type] [description]
     */
    public function meeting()
    {
        return $this->belongsTo('\Revlv\Procurements\Minutes\MinuteEloquent', 'meeting_id');
    }

    /**
     * [signatory description]
     *
     * @return [type] [description]
     */
    public function signatory()
    {
        return $this->belongsTo('\Revlv\Settings\Signatories\SignatoryEloquent', 'signatory_id');
    }

}
