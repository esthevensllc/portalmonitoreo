<?php

namespace App\Modules\Notifications\Repository;

use Illuminate\Database\Eloquent\Collection;

class NotificationRepository
{
    public function find($app_id){
        return NotificationModel::find($app_id);
    }

    public function get(){
        return NotificationModel::get();
    }
}
