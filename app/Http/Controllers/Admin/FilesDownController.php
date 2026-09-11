<?php

namespace App\Http\Controllers\Admin;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class FilesDownController
{

    public function view($id_tracing)
    {
        $tracingID = $id_tracing;
        $user = backpack_user();
        return view("backpack::filesDown", compact("tracingID","user"));
    }

    public function validFileExist(Request $request)
	{
        $path = "/usr/reportes";
		if($request->post("path") == "0"){
			$path = "/var/wwww/html";
		}
		$file = $path . $request->post("fileLink");

        $data = [];
		$data["file"] = $file;
		$data["exist"] = 0;

		if (file_exists($file)){
            $data["exist"] = 1;
        }
        return response()->json($data);
	}

    public function validProcess(Request $request)
    {
		$proceso = $request->get("proceso");
		$fecha = $request->get("fecha");
		$query = "select status from PSO_TYPE where type_name = :p_proceso and type_father = 7148 and type_description = :p_fecha order by status desc";
		$result = DB::select(DB::raw($query), ["p_proceso" => $proceso, "p_fecha" => $fecha]);
		return response()->json(["data" => $result]);
	}
    
	public function loadProcess(Request $request)
    {
		$proceso = $request->post("proceso");
		$fecha = $request->post("fecha");
        $data = DB::statement("call PK_PSO_REPORTES.SP_ADD_REPORTDATE(:p_proceso, :p_fecha)", [
            'p_proceso' => $proceso,
            'p_fecha' => $fecha,
        ]);
		return response()->json($data);
	}
    
}
