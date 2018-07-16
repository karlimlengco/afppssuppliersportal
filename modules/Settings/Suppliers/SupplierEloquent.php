<?php

namespace Revlv\Settings\Suppliers;

use Illuminate\Database\Eloquent\Model;

class SupplierEloquent extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'suppliers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'owner',
        'address',
        'tin',
        'bank_id',
        'branch',
        'account_number',
        'account_type',
        'cell_1',
        'cell_2',
        'phone_1',
        'phone_2',
        'fax_1',
        'line_of_business',
        'fax_2',
        'email_1',
        'email_2',
        'status',

        'is_blocked',
        'date_blocked',
        'blocked_remarks',
    ];

    protected $casts = [
        'id' => 'string'
    ];

    public $incrementing = false;

    /**
     *  Setup model event hooks
     */
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            if($model->id == null)
            {
              $model->id = (string) \Uuid::generate();
            }
        });
    }

    /**
     * [attachments description]
     *
     * @return [type] [description]
     */
    public function attachments()
    {
         return $this->hasMany('\Revlv\Settings\Suppliers\Attachments\AttachmentEloquent', 'supplier_id');
    }

    /**
     * [attachments description]
     *
     * @return [type] [description]
     */
    public function attachmentByType($type)
    {
         return $this->hasOne('\Revlv\Settings\Suppliers\Attachments\AttachmentEloquent', 'supplier_id')->where('type','=',$type)->first();
    }

}
