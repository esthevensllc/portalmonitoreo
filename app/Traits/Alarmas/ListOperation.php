<?php

namespace App\Traits\Alarmas;

use DateTime;

trait ListOperation
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;

    public function index()
    {
        if($this->crud->getRequest()->get("occurrencetime") === null){
            $dt = DateTime::createFromFormat("Y-m-d H:i:s", (new DateTime())->format("Y-m-d")." 00:00:00");
            $str_to = $dt->format("Y-m-d")." 23:59:59";
            $dt->modify("-3 month");
            $str_from = $dt->format("Y-m-d H:i:s");
            $alarmas_filter = urlencode(json_encode(["from" => $str_from, "to" => $str_to]));
            return redirect("alarmas/{$this->id_tracing}?occurrencetime=".$alarmas_filter);
        }
        
        $this->crud->hasAccessOrFail('list');

        $this->data['crud'] = $this->crud;
        $this->data['title'] = $this->crud->getTitle() ?? mb_ucfirst($this->crud->entity_name_plural);

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view($this->crud->getListView(), $this->data);
    }
}
