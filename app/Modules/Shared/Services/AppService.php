<?php

namespace App\Modules\Shared\Services;

class AppService
{
    public function response($errors, $data = null){
        return new Response($errors, $data)
    }
}
