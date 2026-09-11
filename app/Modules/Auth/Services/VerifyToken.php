<?php

namespace App\Modules\Auth\Services;

use App\Modules\Auth\Entities\Operations;
use App\Modules\Shared\Services\Response;

class VerifyToken
{
    private $expectedToken = '$2y$10$OsqslG2Fx/8VXNhgyDCoqOa6LjI8Rd7enOMMvgsddekgajR1Zpwb6';
    private $expectedQueryToken = '$2y$10$WjCTni.B54leeMXt2IIPguZ8Fa9iuRhP.8OHgFsGSmdoQIiq50l0i';

    public function __invoke($token, $operation): Response
    {
        if($this->expectedToken === $token){
            if($operation === Operations::SAVE_ERROR_LOG){
                return new Response([]);
            }
            return new Response(['message' => 'Forbidden'], 403);
        }
        if($this->expectedQueryToken === $token){
            if($operation === Operations::GET_ERROR_LOG){
                return new Response([]);
            }
            return new Response(['message' => 'Forbidden'], 403);
        }
        return new Response(['message' => 'Incorrect token'], 401);
    }
}
