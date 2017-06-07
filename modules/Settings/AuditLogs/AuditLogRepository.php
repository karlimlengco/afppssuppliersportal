<?php

namespace Revlv\Settings\AuditLogs;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class AuditLogRepository extends BaseRepository
{
    use  DatatableTrait;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return AuditLogEloquent::class;
    }

}
