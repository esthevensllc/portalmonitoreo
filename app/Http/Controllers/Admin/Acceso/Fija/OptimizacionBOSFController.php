<?php

namespace App\Http\Controllers\Admin\Acceso\Fija;

use AMovil\Shared\Exports\Domain\WriterType;
use AMovil\Shared\Exports\Domain\ExportService;
use AMovil\Shared\Application\Response;
use PhpOffice\PhpSpreadsheet\Style\Border;
use App\Http\Controllers\Admin\BaseListController;
use App\Traits\DB\ProcedureTrait;
use Illuminate\Http\Request;
use DateTime;
use PDO;

class OptimizacionBOSFController extends BaseListController
{
    use \App\Traits\Filters\CriteriaFilterTrait;
    use ProcedureTrait;

    protected function beforeSetup()
    {
        $this->base_module_id = 12106;
        parent::beforeSetup();
        $this->fields["prioridad_remedy"]["type"] = "closure";
        $this->fields["prioridad_remedy"]["function"] = function($entry){
            if($entry->prioridad_remedy === "ALTA"){
                return "<p class='m-0 font-weight-bold text-danger'>{$entry->prioridad_remedy}</p>";
            }else if($entry->prioridad_remedy === "MEDIA"){
                return "<p class='m-0 font-weight-bold' style='color: #e67e22;'>{$entry->prioridad_remedy}</p>";
            }else if($entry->prioridad_remedy === "BAJA"){
                return "<p class='m-0 font-weight-bold text-warning'>{$entry->prioridad_remedy}</p>";
            }
            return "<p class='m-0'>{$entry->prioridad_remedy}</p>";
        };

        $this->fields["reclamos_ivr_dia"]["type"] = "closure";
        $this->fields["reclamos_ivr_dia"]["function"] = function($entry){
            $date = new \DateTime($entry->fecha_de_caida);
            $formatDate = $date->format('d/m/Y');
            return "<button class='btn btn-link btn-sm btn-bosf-view' data-column='reclamos_ivr_dia' data-incidencia='{$entry->incidencia_remedy}' data-fecha='{$formatDate}'>{$entry->reclamos_ivr_dia}</button>";
        };

        $this->fields["reclamos_del_dia"]["type"] = "closure";
        $this->fields["reclamos_del_dia"]["function"] = function($entry){
            $date = new \DateTime($entry->fecha_de_caida);
            $formatDate = $date->format('d/m/Y');
            return "<button class='btn btn-link btn-sm btn-bosf-view' data-column='reclamos_del_dia' data-plano='{$entry->plano}' data-fecha='{$formatDate}'>{$entry->reclamos_del_dia}</button>";
        };

        $this->fields["nro_reincidencias_dia"]["type"] = "closure";
        $this->fields["nro_reincidencias_dia"]["function"] = function($entry){
            $date = new \DateTime($entry->fecha_de_caida);
            $formatDate = $date->format('d/m/Y');
            return "<button class='btn btn-link btn-sm btn-bosf-view' data-column='nro_reincidencias_dia' data-plano='{$entry->plano}' data-fecha='{$formatDate}'>{$entry->nro_reincidencias_dia}</button>";
        };

        $this->fields["nro_reincidencias_sem"]["type"] = "closure";
        $this->fields["nro_reincidencias_sem"]["function"] = function($entry){
            $date = new \DateTime($entry->fecha_de_caida);
            $formatDate = $date->format('d/m/Y');
            return "<button class='btn btn-link btn-sm btn-bosf-view' data-column='nro_reincidencias_sem' data-plano='{$entry->plano}' data-fecha='{$formatDate}'>{$entry->nro_reincidencias_sem}</button>";
        };

        $this->fields["nro_reincidencias_mes"]["type"] = "closure";
        $this->fields["nro_reincidencias_mes"]["function"] = function($entry){
            $date = new \DateTime($entry->fecha_de_caida);
            $formatDate = $date->format('d/m/Y');
            return "<button class='btn btn-link btn-sm btn-bosf-view' data-column='nro_reincidencias_mes' data-plano='{$entry->plano}' data-fecha='{$formatDate}'>{$entry->nro_reincidencias_mes}</button>";
            
        };

        $this->fields["clientes_referidos"]["type"] = "closure";
        $this->fields["clientes_referidos"]["function"] = function($entry){
            $date = new \DateTime($entry->fecha_de_caida);
            $formatDate = $date->format('d/m/Y');
            return "<button class='btn btn-link btn-sm btn-bosf-view' data-column='clientes_referidos' data-plano='{$entry->plano}'>{$entry->clientes_referidos}</button>";
        };

        $this->fields["cant_sots"]["type"] = "closure";
        $this->fields["cant_sots"]["function"] = function($entry){
            $date = new \DateTime($entry->fecha_de_caida);
            $formatDate = $date->format('d/m/Y');
            return "<button class='btn btn-link btn-sm btn-bosf-view' data-column='cant_sots' data-plano='{$entry->plano}' data-fecha='{$formatDate}'>{$entry->cant_sots}</button>";
        };

        $this->fields["cant_pext"]["type"] = "closure";
        $this->fields["cant_pext"]["function"] = function($entry){
            $date = new \DateTime($entry->fecha_de_caida);
            $formatDate = $date->format('d/m/Y');
            return "<button class='btn btn-link btn-sm btn-bosf-view' data-column='cant_pext' data-plano='{$entry->plano}' data-fecha='{$formatDate}'>{$entry->cant_pext}</button>";
        };

        $this->fields["cantidad_trabajos"]["type"] = "closure";
        $this->fields["cantidad_trabajos"]["function"] = function($entry){
            $date = new \DateTime($entry->fecha_de_caida);
            $formatDate = $date->format('d/m/Y');
            return "<button class='btn btn-link btn-sm btn-bosf-view' data-column='cantidad_trabajos' data-plano='{$entry->plano}' data-fecha='{$formatDate}'>{$entry->cantidad_trabajos}</button>";
        };

        $this->route = config('backpack.base.route_prefix')."/optimizacion-bosf/{$this->id_tracing}";
        $str_date = (new DateTime())->format("YmdHis");
        $this->exportOptions = [
            "filename" => "{$this->trac_name}_{$this->module_name}_{$str_date}.xlsx",
            "type" => WriterType::XLSX,
        ];
    }

    protected function loadFilters(){
        // parent::loadFilters();
        foreach($this->fields as $field => $config){
            $label = strtoupper($field);
            if(array_key_exists("label", $config)){
                $label = $config["label"];
            }
            $type = "default";
            if($field !== "fecha_inc"){            
                $this->loadCriteriaFilter($field, $label, $type);                
            }else{
                $this->crud->addFilter([   // date_range
                    'type' => 'date_range', // db columns for start_date & end_date
                    'name' => 'fecha_inc', // db columns for start_date & end_date
                    'label' => 'Intervalo fechas'
                ], false, function($value){
                    $dates = json_decode($value);
                    $this->crud->addClause('where', 'fecha_inc', '>=', $dates->from);
                    $this->crud->addClause('where', 'fecha_inc', '<=', $dates->to);
                });
            }
        }
    }

    public function index()
    {
        if($this->crud->getRequest()->get("estado_interno") === null){            
            $filter = urlencode(json_encode(["operator" => "not_in", "value" => "CANCELADO,CERRADO"]));
            return redirect("optimizacion-bosf/19?menu=FIJA-Optimizaci%C3%B3n%20BOSF&estado_interno=".$filter);
        }

        $this->crud->hasAccessOrFail('list');

        $this->data['crud'] = $this->crud;
        $this->data['title'] = $this->crud->getTitle() ?? mb_ucfirst($this->crud->entity_name_plural);

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view($this->crud->getListView(), $this->data);
    }

    protected function setupListOperation(){
        parent::setupListOperation();
        /*
        CAMPOS_CLICK = RECLAMOS_DEL_DIA, NRO_REINCIDENCIAS_DIA, NRO_REINCIDENCIAS_SEM, NRO_REINCIDENCIAS_MES, CLIENTES_REFERIDOS, CANT_SOTS, CANT_PEXT
        
        $this->crud->addColumn([
            "label" => "RECLAMOS_q",
            "name" => "reclamos_del_dia",
            "type" => "closure",
            "function" => function($entry){
                return "<button class='btn btn-link btn-sm btn-bosf-view'>{$entry->reclamos_del_dia}</button>";
            },
        ]);
        */
        $this->crud->orderBy("fecha_inc", "desc");
    }

    public function modal(Request $request){
        $columna = $request->get("columna");             

        switch($columna){
            case 'reclamos_del_dia':
                $procedure = "PK_FIJA_BOSF.SP_FIJA_RECLAMOS_BOSF";
                $plano = $request->get("plano");
                $fecha = $request->get("fecha");
                return $this->getDetallePlanoFecha($procedure, $plano, $fecha, $columna);
                break;
            case 'nro_reincidencias_dia':
                $procedure = "PK_FIJA_BOSF.SP_FIJA_RECURRENCIA_DIARIO_BOSF";
                $plano = $request->get("plano");
                $fecha = $request->get("fecha");
                return $this->getDetallePlanoFecha($procedure, $plano, $fecha, $columna);
                break;
            case 'nro_reincidencias_sem':
                $procedure = "PK_FIJA_BOSF.SP_FIJA_RECURRENCIA_SEM_BOSF";
                $plano = $request->get("plano");
                $fecha = $request->get("fecha");
                return $this->getDetallePlanoFecha($procedure, $plano, $fecha, $columna);
                break;
            case 'nro_reincidencias_mes':
                $procedure = "PK_FIJA_BOSF.SP_FIJA_RECURRENCIA_MES_BOSF";
                $plano = $request->get("plano");
                $fecha = $request->get("fecha");
                return $this->getDetallePlanoFecha($procedure, $plano, $fecha, $columna);
                break;
            case 'clientes_referidos':
                $procedure = "PK_FIJA_BOSF.SP_FIJA_CLIENTES_REFERIDOS_BOSF";    
                $plano = $request->get("plano"); 
                $fecha = $request->get("fecha");           
                return $this->getDetallePlano($procedure, $plano, $fecha, $columna);
                break;
            case 'cant_sots':
                $procedure = "PK_FIJA_BOSF.SP_FIJA_SOTS_BOSF";
                $plano = $request->get("plano");
                $fecha = $request->get("fecha");
                return $this->getDetallePlanoFecha($procedure, $plano, $fecha, $columna);
                break;
            case 'cant_pext':
                $procedure = "PK_FIJA_BOSF.SP_FIJA_PEXTS_BOSF";
                $plano = $request->get("plano");
                $fecha = $request->get("fecha");
                return $this->getDetallePlanoFecha($procedure, $plano, $fecha, $columna);
                break;    
            case 'cantidad_trabajos':
                $procedure = "PK_FIJA_BOSF.SP_FIJA_TRABAJOS_PROGRAMADOS";
                $plano = $request->get("plano");
                $fecha = $request->get("fecha");
                return $this->getDetallePlano($procedure, $plano, $fecha, $columna);
                break;
            case 'reclamos_ivr_dia':
                $procedure = "PK_FIJA_BOSF.sp_fija_reclamos_ivr_bosf";
                $incidencia = $request->get("incidencia");
                $fecha = $request->get("fecha");
                return $this->getDetalleIncidenciaFecha($procedure, $incidencia, $fecha, $columna);
                break; 
        }
    }

    public function getDetallePlanoFecha($procedure, $plano, $fecha, $columna){
        $procedure = "begin {$procedure}(:plano, :fecha, :resultado); end;";
        $data = $this->executeProcedure($procedure, [
            "plano" => ["value" => $plano, "type" => PDO::PARAM_STR],
            "fecha" => ["value" => $fecha, "type" => PDO::PARAM_STR],
            "resultado" => [],
        ]);
        return [$columna, $plano, $fecha, $data];
    }

    public function getDetallePlano($procedure, $plano, $fecha, $columna){
        $procedure = "begin {$procedure}(:plano, :resultado); end;";
        $data = $this->executeProcedure($procedure, [
            "plano" => ["value" => $plano, "type" => PDO::PARAM_STR],
            "resultado" => [],
        ]);
        return [$columna, $plano, $fecha, $data];
    }

    public function getDetalleIncidenciaFecha($procedure, $incidencia, $fecha, $columna){
        $procedure = "begin {$procedure}(:v_incidencia, :fecha, :resultado); end;";
        $data = $this->executeProcedure($procedure, [
            "v_incidencia" => ["value" => $incidencia, "type" => PDO::PARAM_STR],
            "fecha" => ["value" => $fecha, "type" => PDO::PARAM_STR],
            "resultado" => [],
        ]);
        return [$columna, $incidencia, $fecha, $data];
    }

    public function exportDetalle(Request $request)
    {
        $exportService = app(ExportService::class);

        $data = $this->modal($request);

        $options = [
            "rowType" => "array",
            "sheetIndex" => 0,
            'title' => "Optimización BOSF",
            'styles' => [
                'header' => [
                    'font' => ['bold' => true, 'size' => 9],
                    'borders'=> [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => array('rgb'=>'000000')
                        ]
                    ]
                ],
                'body' => [
                    'font' => ['size' => 9]
                ]
            ]
        ];

        $headers = array_keys($data[3][0]);


        foreach( $headers as $header ){
            $headers_f[$header] = ["label" => $header];
        }

        $exportService->loadData($headers_f, $data[3], $options);
        $exportContent = $exportService->getWriter(WriterType::XLS)->getOutput();

        $filename = "Optimizacion_BOSF.xls";

        $headers_type = [
            'csv' => [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment;filename="'.$filename.'"'
            ],
            'xls' => [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment;filename="'.$filename.'"'
            ],
        ];

        return response($exportContent, 200, $headers_type['xls']);
    }
}
