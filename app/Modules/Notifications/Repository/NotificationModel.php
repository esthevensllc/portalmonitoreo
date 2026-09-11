<?php

namespace App\Modules\Notifications\Repository;

use Illuminate\Database\Eloquent\Model;

class NotificationModel extends Model
{
    protected $table = 'padm_replogs_notification';
    protected $primaryKey = 'app_id';
    public $incrementing = false;
    public $timestamps = false;
}
