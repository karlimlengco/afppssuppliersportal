<?php

namespace Revlv\Library\Library;

use Revlv\BaseRequest;

class LibraryRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected $whitelist = [
        'name',
        'catalog_id',
        'tags',
        'file_name',
        'uploaded_by',
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'                 => 'required',
            'catalog_id'           => 'required',
            'file_name'            => 'required',
        ];
    }
}