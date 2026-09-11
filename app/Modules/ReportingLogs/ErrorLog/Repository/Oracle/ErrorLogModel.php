<?php

namespace App\Modules\ReportingLogs\ErrorLog\Repository\Oracle;

use Illuminate\Database\Eloquent\Model;

class ErrorLogModel extends Model
{
    protected $table = 'REPLOGS_ERRORLOG';
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $timestamps = false;
}
