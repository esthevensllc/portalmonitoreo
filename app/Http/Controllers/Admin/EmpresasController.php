<?php

namespace App\Http\Controllers\Admin;

use AMovil\Shared\Exports\Domain\WriterType;
use App\Facads\ODB;
use App\Traits\DB\ProcedureTrait;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory as IOFactorySpread;			
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class EmpresasController extends BaseListController
{
    use ProcedureTrait;
    protected function beforeSetup()
    {
        $this->base_module_id = 10804;// 10142
        parent::beforeSetup();
        // dd(PSO_BASE_TABLE_QUERY);
        $this->route = config('backpack.base.route_prefix')."/empresas/{$this->id_tracing}";
        $str_date = (new DateTime())->format("YmdHis");
        $this->exportOptions = [
            "filename" => "{$this->trac_name}_{$this->module_name}_{$str_date}.xlsx",
            "type" => WriterType::XLSX,
        ];
    }

	    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        $this->crud->setSubheading(' ','create');
		parent::setup();
    }

    protected function loadFilters()
    {
        parent::loadFilters();
        $this->crud->addClause('where', 'usuario', '=', backpack_user()->username);
    }

    protected function setupListOperation()
    {		
        $this->crud->buttonOptions = [
			'title' => ['h5' => 'BÚSQUEDA DE FACTIBILIDAD MASIVA DE FIJA Y MÓVIL'],
			'plantilla' => [],
            'asyncImport' => [
                'url' => asset(config('backpack.base.route_prefix')."/empresas/{$this->id_tracing}/import")
            ]
        ];
        //dd(backpack_user());
        parent::setupListOperation();
		$this->crud->addButtonFromView('line', 'verReporte', 'verReporte', 'beginning');
    }

    public function import($id_tracing, Request $request)
	{
		$tracingID = $request->post("tracID");
		$user = backpack_user();
		$userID = $user->usuario;
		$fieldsHead = 6;
		$formID = 0;
		$typeUnique = "";
		$fileName = $request->file('fileImport')->getPathname();
		$rowVal = 0;
		
		$inputFileType = IOFactorySpread::identify($fileName);
		$objReader = IOFactorySpread::createReader($inputFileType);
		$objPHPExcel = $objReader->load($fileName);
		$sheet = $objPHPExcel->getSheet(0);
		$highestRow = $sheet->getHighestRow();
		$highestColumn = $sheet->getHighestColumn();
		$fieldsValues = array();
		$errorSheet = array();
		$isLoad = true;
		$countColumn = 1;
		while (true) {
			$rowColVal = $sheet->getCellByColumnAndRow($countColumn,1)->getValue();
			if(!isset($rowColVal)||$rowColVal=='')
				break;
			$countColumn++;
		}
        $countColumn--;
		$errorFile = "";
		if($objPHPExcel->getSheetCount()>1){
			$errorFile = "Cargar el archivo solo con una hoja.";
		}elseif($countColumn<>$fieldsHead){
			$errorFile = "La cantidad de columnas no coincide con la base datos, vuelva a cargar nuevamente.";
		}elseif($highestRow>40000){
			$errorFile = "La cantidad de registros supera el limite de 40000 registros, vuelva a cargar nuevamente.";
		}else{
			for ($row = 2; $row <= $highestRow; $row++){
				$rowValArray = array();
				$colNum = 1;
				$errorRow = array();
				$errorSheet = array();
				for ($i = 0; $i < 6; $i++) {
					$rowColVal = $sheet->getCellByColumnAndRow($colNum,$row)->getValue();
					$colNum++;
					$isLoad = true;
					$rowValArray[] = trim($rowColVal);
				}
				if(!($rowValArray[0] !== "" && $rowValArray[1] !== "" && $rowValArray[2] !== ""
					&& $rowValArray[3] !== "" && $rowValArray[4] !== "" && $rowValArray[5] !== "")){
					continue;
				}

				if(count($errorRow)>0){
					$isLoad = false;
					$errorSheet["Fila ".$row]=$errorRow;
				}elseif($isLoad){
					$fieldsValues[]=$rowValArray;
				}
				unset($errorRow);
				unset($rowValArray);
			}
		}

		if($errorFile!=""){
            return response()->json(["passes" => false, "message" => $errorFile]);
		}

		if(count($fieldsValues)>0){
			$username = $user->username;
			//$id_proceso = $this->index->setProcesoEmpresas($username);

			$indexLoop = 0;

			try {
				// Definir reglas de validación para cada campo
				$rules = [
					'campo1' => 'integer',
					'campo2' => 'string',
					'campo3' => 'string',
					'campo4' => 'nullable',
					'campo5' => 'numeric',
					'campo6' => 'numeric'
				];

				// Definir mensajes personalizados para las reglas
				$messages = [
					'campo1.integer' => 'El ID debe ser un número entero.',
					'campo1.required' => 'El ID es obligatorio.',
					'campo2.required' => 'El RUC es obligatorio.',
					'campo3.required' => 'El NOMBRE_EMPRESA es obligatorio.',
					'campo5.numeric' => 'La LATITUD_Y debe ser una coordenada.',
					'campo5.required' => 'La LATITUD_Y es obligatoria.',
					'campo6.numeric' => 'La LONGITUD_X debe ser una coordenada.',
					'campo6.required' => 'La LONGITUD_X es obligatoria.'
				];

				foreach ($fieldsValues as $index => $incRow) {   
					$indexLoop = $index;
					// Crear el array asociativo con los datos a validar
					if(str_contains($incRow[4], "0.00000")){
						$incRow[4] = "-7.777777";
						$fieldsValues[$index][4] =  "-7.777777";
					}
					if(str_contains($incRow[5], "0.00000")){
						$incRow[5] = "-77.777777";
						$fieldsValues[$index][5] =  "-77.777777";
					}
					$data = [
						'campo1' => is_numeric($incRow[0]) ? (int) $incRow[0] : null,
						'campo2' => $incRow[1],
						'campo3' => $incRow[2],
						'campo4' => $incRow[3],
						'campo5' => is_numeric($incRow[4]) ? (float) $incRow[4] : null,
						'campo6' => is_numeric($incRow[5]) ? (float) $incRow[5] : null
					];

					// Realizar la validación
					$validator = Validator::make($data, $rules, $messages);

					// Lanzar una excepción si la validación falla
					if ($validator->fails()) {
						throw new ValidationException($validator);
					}
				}

			} catch (ValidationException $e) {
				// Manejar el error de validación, por ejemplo, enviar un mensaje al usuario
				$errors = $e->errors();
				$errorMessage = "Error de validación fila (".($indexLoop+1)."): " . implode(', ', array_flatten($errors));
				// Puedes redirigir al usuario a una página de error o mostrar un mensaje en la vista
				return response()->json(["passes" => false, "message" => $errorMessage]);
			} catch (\Exception $e) {
				// Manejar otros errores
				return response()->json(["passes" => false, "message" => $e->getMessage()]);
			}

			/*$id_proceso = $this->executeProcedure("BEGIN PSO_OSSPORTAL.SP_SET_PROCESO_EMPRESAS(:typeName, :resultado); END;", [
                "typeName" => ["value" => $username, "type" => ODB::CHAR],
                "resultado" => ["type" => ODB::CURSOR],
            ]);
            if(count($id_proceso)>0){
                $id_proceso = $id_proceso[0];
            }

			$id_proceso = $id_proceso["ID_PROCESO"];			

			foreach ($fieldsValues as $incRow) {
                DB::statement("call PSO_OSSPORTAL.SP_SET_EMPRESAS(:id, :ruc, :nombre_empresa, :tipo_sede, :longitud_x, :latitud_y, :id_proceso)", [
                    'id' => $incRow[0],
                    'ruc' => $incRow[1],
                    'nombre_empresa' => $incRow[2],
                    'tipo_sede' => $incRow[3],
                    'longitud_x' => $incRow[5],
                    'latitud_y' => $incRow[4],
                    'id_proceso' => $id_proceso
                ]);
			}*/

			$this->importProcess($username, $fieldsValues);
		}

        return response()->json(["passes" => true, "message" => "Procesado Correctamente."]);
		
	}

	private function importProcess($username, $fieldsValues){
		$fechaInicio = new DateTime();
		$chunkLimit = 30;

		DB::table('proceso_fija_empresas_planos')->insert([
			'usuario' => $username,
			'fecha_inicio' => $fechaInicio->format('Y-m-d H:i:s'),
			'estado' => 0
		]);

		$idProceso = DB::table('proceso_fija_empresas_planos')
		->where('usuario', $username)
		->where('fecha_inicio', $fechaInicio->format('Y-m-d H:i:s'))
		->first();
		$idProceso = $idProceso !== null ? $idProceso->id_proceso : null;

		$chunk = [];
		$lastIndex = count($fieldsValues)-1;

		foreach ($fieldsValues as $index => $incRow) {
			$chunk[] = [
				'id' => $incRow[0],
				'ruc' => $incRow[1],
				'nombre_emp' => $incRow[2],
				'nombre_sede' => $incRow[3],
				'longitud_x' => $incRow[5],
				'latitud_y' => $incRow[4],
				'id_proceso' => $idProceso
			];
			if (count($chunk) >= $chunkLimit || $lastIndex === $index) {
				DB::table("fija_empresas_planos")->insert($chunk);
				$chunk = [];
			}
		}

		DB::table('proceso_fija_empresas_planos')
		->where('usuario', $username)
		->where('fecha_inicio', $fechaInicio->format('Y-m-d H:i:s'))
		->update(['estado' => 1]);

		try {
			DB::statement("call pk_servicios_inventario.sp_consultacorporativo(:id)", [
				'id' => $idProceso
			]);
	
			DB::table('proceso_fija_empresas_planos')
			->where('id_proceso', $idProceso)
			->update([
				'estado' => 2,
				'fecha_fin' => (new DateTime())->format('Y-m-d H:i:s'),
				'mensaje' => null
			]);
		} catch (\Throwable $th) {
			DB::table('proceso_fija_empresas_planos')
			->where('id_proceso', $idProceso)
			->update([
				'estado' => -1,
				'fecha_fin' => (new DateTime())->format('Y-m-d H:i:s'),
				'mensaje' => $th->getMessage()
			]);
		}
	}

	public function export()
    {
        $rutaArchivo = public_path('files/INFO_CLIENTES.xlsx');

        if (file_exists($rutaArchivo)) {
            return response()->download($rutaArchivo);
        } else {
            abort(404, 'El archivo no se encontró en el servidor.');
        }
    }

	public function exportDataEmpresas($id_tracing, $idProceso)
	{
		
		//$sections = $this->searchViewExportEmpresas($idProceso);
		$sections = [];
		$sections["bodyTbl"] = DB::select(DB::raw('SELECT A.*,ROWNUM F1 FROM (
			SELECT ID,RUC,NOMBRE_EMP,NOMBRE_SEDE,LONGITUD_X, LATITUD_Y, NOMBDEP, NOMBPROV, NOMBDIST, PLANO_FTTH,Round(DIST_FTTH,2) DIST_FTTH, PLANO_HFC, Round(DIST_HFC,2) DIST_HFC , "2G", "3G", "4G" , "4.5G", "5G", SITE_MOVIL_CERCANO, DIST_SITE_MOVIL_CERCANO, MEDIO_TX_SITE from fija_empresas_planos f
			inner join proceso_fija_empresas_planos p
			on p.id_proceso = f.id_proceso WHERE p.id_proceso = ?
		) A'), [$idProceso]);
		$sections["headTbl"] = [];
		if(count($sections["bodyTbl"]) > 0){
			$sections["headTbl"] = array_keys(json_decode((json_encode($sections["bodyTbl"][0])), true));
		}
		
		$headTbl = $sections["headTbl"];
		
		foreach (array("FILA", "F1") as $itemExclude) {
			$keyIN = array_search($itemExclude, $headTbl);
			if (isset($keyIN) && is_numeric($keyIN))
				unset($headTbl[$keyIN]);
		}
		$keyIN = array_search("ID", $headTbl);
		if (isset($keyIN) && is_numeric($keyIN))
			$headTbl[$keyIN] = strtolower($headTbl[$keyIN]);

		$fileName = $idProceso . "_" . date("YmdHis") . ".csv";

		//dd(array_values($headTbl));
		$head_csv = array_map(function($value){
			return strtoupper($value);
		}, array_values($headTbl));

		header("Content-Type: application/force-download");
		header("Content-Type: application/octect-stream");
		header("Content-Type: application/download");
		header("Content-Type: text/x-csv");
		header("Content-Type: application/csv");
		header("Content-Transfer-Encoding: binary");

		header("Content-Disposition: attachment;filename={$fileName}");
		$fileCsv = fopen('php://output', 'w', $encoding = "utf-8");
		
		fputcsv($fileCsv, array_values($headTbl));

		foreach ($sections["bodyTbl"] as $rowXls) {
			$rowData = array();
			foreach ($headTbl as $key => $headItem) {
				$keyData = $headItem;
				$keyData = explode(" ", $keyData);
				$keyData = end($keyData);
				$rowData[] = strtoupper($rowXls->{$keyData});
			}
			fputcsv($fileCsv, $rowData);
		}
		fpassthru($fileCsv);
		fclose($fileCsv);
	}
}
