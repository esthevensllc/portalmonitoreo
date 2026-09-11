<?php

namespace App\Modules\ReportingLogs\ErrorLog\Entities;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class ErrorLevel
{
    public const ERROR = 1;
    public const WARNING = 2;
    private static $mappedException = [
        NotFoundHttpException::class => self::WARNING,
        \Illuminate\Validation\ValidationException::class => self::WARNING,
        \Illuminate\Session\TokenMismatchException::class => self::WARNING,
    ];

    public static function isError(int $error_level){
        return self::ERROR === $error_level;
    }

    public static function isWarning(int $error_level){
        return self::WARNING === $error_level;
    }

    public static function mapFromException(Throwable $e){
        if(isset(self::$mappedException[get_class($e)])){
            return self::$mappedException[get_class($e)];
        }
        return self::ERROR;
    }
}
