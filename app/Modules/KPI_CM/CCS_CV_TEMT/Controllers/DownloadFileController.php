<?php

namespace App\Modules\KPI_CM\CCS_CV_TEMT\Controllers;

use Illuminate\Support\Facades\Storage;

class DownloadFileController
{
    function __invoke($fileName)
    {
        try {
            return Storage::download('cm_ccs_cv_temt/'.$fileName);
        } catch (\Throwable $th) {
            return response()->json([], 404);
        }
    }
}
