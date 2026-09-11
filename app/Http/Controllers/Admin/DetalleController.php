<?php

namespace App\Http\Controllers\Admin;

use AMovil\Shared\Exports\Domain\WriterType;
use DateTime;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class DetalleController extends BaseListController
{
    protected function beforeSetup()
    {
        $this->base_module_id = 10142;// 10142
        parent::beforeSetup();
        $this->route = config('backpack.base.route_prefix')."/detalle/{$this->id_tracing}";

        $str_date = (new DateTime())->format("YmdHis");
        $this->exportOptions = [
            "filename" => "{$this->trac_name}_{$this->module_name}_{$str_date}.csv",
            "type" => WriterType::CSV,
        ];
    }

    public function index()
    {
        if($this->crud->getRequest()->get("trimestre") === null){
            $filters = ["trimestre" => ["operator" => "equals", "value" => "(SELECT max(TRIMESTRE) as value FROM PSO_0DATA_27_10142)"]];
            $str_filters = [];
            foreach($filters as $i => $row){
                if(str_contains(strtoupper($row["value"]), "SELECT")){
                    $resp = DB::select($row["value"]);
                    if(count($resp) === 0){
                        throw new InvalidArgumentException("El filtro predeterminado para $i no es válido");
                    }
                    if(!isset($resp[0]->value)){
                        throw new InvalidArgumentException("El filtro predeterminado para $i no es válido");
                    }
                    $row["value"] = $resp[0]->value;
                }
                $str_filters[] = $i."=".urlencode(json_encode($row));
            }
            $str_filters = implode("&", $str_filters);
            //dd($str_filters);
            return redirect("detalle/{$this->id_tracing}?".$str_filters);
        }

        $this->crud->hasAccessOrFail('list');

        $this->data['crud'] = $this->crud;
        $this->data['title'] = $this->crud->getTitle() ?? mb_ucfirst($this->crud->entity_name_plural);

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view($this->crud->getListView(), $this->data);
    }
}
