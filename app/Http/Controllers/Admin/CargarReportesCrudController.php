<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CargarReportesCrudController extends Controller
{
    public function index()
    {
        return view('backpack::carga_reportes');
    }

    public function reporteCapacidadProcedure(){
        $fechaInicio = $_GET['fechaInicio'];
        $fechaFin = $_GET['fechaFin'];
        $statement = DB::connection()->getPdo()->prepare("CALL PK_PRG_MTC_PROCESOS.SP_PRG_MTC_Capacidad(?,?)");
            
        $statement->bindParam(1, $fechaInicio);
        $statement->bindParam(2, $fechaFin);

        $statement->execute();
    }
}
