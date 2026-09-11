<?php

namespace App\Http\Middleware;

use App\Services\Notification\NotifyError;
use Closure;
use Illuminate\Support\Facades\DB;

class ErrorHandlerException
{
    public function handle($request, Closure $next)
    {
        try {
            if($request->path() != "/" && $request->path() != "dashboard" && $request->path() != "login" && session('id_session')){
                if($request->input('menu')!=null){
                    $param = explode('-',$request->input('menu'));
                    $menu = $param[0];
                    $submenu = $param[1];
                }else{
                    $menu = '-';
                    $submenu = '-';
                }
                DB::table('padm_session_access')->insert(
                    ['session_id' => session('id_session')
                    ,'menu'=> urldecode($menu)
                    ,'sub_menu'=> urldecode($submenu)
                    ,'url' => str_replace('/search','',url()->full())
                    ]
                );
            }
            // return response()->json([]);
            return $next($request);
        } catch (\Throwable $e) {
            dd($e->getMessage());
            return response()->view('errors.500', [], 500);
        }

    }
}