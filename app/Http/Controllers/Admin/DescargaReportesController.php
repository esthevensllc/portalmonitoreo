<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DescargaReportesController extends Controller
{
    public function index()
    {
        return view('backpack::descarga_reportes');
    }

}