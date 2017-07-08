<?php

namespace Revlv\Settings\Announcements;

use Revlv\BaseRequest;

class AnnouncementRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected $whitelist = [
        'title',
        'message',
        'post_at',
        'expire_at',
        'status',
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'         => 'required',
            'message'       => 'required',
            'post_at'       => 'required',
        ];
    }
}