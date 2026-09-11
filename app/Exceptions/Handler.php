<?php

namespace App\Exceptions;

use App\Modules\ReportingLogs\ErrorLog\Entities\ErrorLevel;
use App\Modules\ReportingLogs\ErrorLog\Services\SaveErrorLog;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    private $service;

    public function __construct(SaveErrorLog $service)
    {
        $this->service = $service;
    }

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];
    // $service->__invoke([
        //     'subject' => "Error portal regulatorio - {$request->path()}",
        //     'message' => "Message: {$e->getMessage()}".
        //     "\nRuta: {$request->fullUrl()}".
        //     "\nMethod: {$request->method()}".
        //     "\nFile: {$e->getFile()}".
        //     "\nLine: {$e->getLine()}"
        // ]);

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        
    }

    function report(Throwable $e)
    {
        /*$method_url = $_SERVER['REQUEST_METHOD'].': '.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $this->service->__invoke([
            'app_id' => 'portalmonitoreov2',
            'e_level' => ErrorLevel::mapFromException($e),
            'env' => 'desarrollo',
            'message' => $method_url.
                "\nMessage: {$e->getMessage()}".
                "\nException: ".get_class($e).
                "\nFile: {$e->getFile()}".
                "\nLine: {$e->getLine()}\n",
            'stacktrace' => $e->getTraceAsString()
        ]);*/

    }

}