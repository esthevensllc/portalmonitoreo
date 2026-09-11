<?php

namespace App\Modules\KPI_CM\CVM\Controllers;

use Illuminate\Support\Facades\Storage;

class DownloadFileController
{
    function __invoke($fileName)
    {
        try {
            return Storage::download('cm_cvm/'.$fileName);
        } catch (\Throwable $th) {
            return response()->json([], 404);
        }
    }
}
