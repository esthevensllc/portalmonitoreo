<?php

namespace App\Modules\ReportingLogs\ErrorLog\Repository\Oracle;

use DB;

class OraErrorLogRepository
{
    public function save($data){
        DB::table('padm_replogs_errorlog')->insert([
            'app_id' => $data['app_id'],
            'e_level' => $data['e_level'],
            'env' => $data['env'],
            'date_occurred' => $data['date_occurred'],
            'message' => $data['message'],
            'stacktrace' => $data['stacktrace'],
            'occurred_on' => $data['occurred_on']
        ]);
    }

    public function deleteByAppIdAndDate($app_id, $date){
        return DB::table('padm_replogs_errorlog')
        ->where('app_id', $app_id)
        ->where('date_occurred', $date)
        ->delete();
    }

    public function deleteAllOfAppId($app_id){
        return DB::table('padm_replogs_errorlog')
        ->where('app_id', $app_id)
        ->delete();
    }

    public function getByAppIdAndDate($app_id, $date){
        return DB::table('padm_replogs_errorlog elog')
            ->select(
                'elog.app_id',
                DB::Raw("(case when lvl.name is not null then lvl.name else cast(elog.e_level as varchar2(100)) end) as e_level"),
                'elog.env', 'elog.date_occurred', 'elog.message', 'elog.stacktrace', 'elog.occurred_on')
            ->leftJoin('padm_replogs_errorlevel lvl', 'lvl.id', '=', 'elog.e_level')
            ->where('elog.app_id', $app_id)
            ->where('elog.date_occurred', $date)
            ->orderby('elog.occurred_on', 'desc')
            ->get();
    }

    public function getDatesWhitErrorLogGroupedByAppId(){
        return DB::table('padm_replogs_errorlog')
            ->select('app_id', DB::Raw("to_char(date_occurred, 'YYYY-MM-DD') as date_occurred"))
            // ->where('app_id', $app_id)
            ->groupBy('app_id', 'date_occurred')
            ->orderBy('app_id', 'desc')
            ->orderBy('date_occurred', 'desc')
            ->get()
            ->groupBy('app_id');
    }
}
