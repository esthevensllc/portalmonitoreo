<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ACCESO_3G_Movil_DesempeñoRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;

/**
 * Class ACCESO_4G_Movil_DesempeñoCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ACCESO_3G_Movil_DesempeñoCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \App\Traits\Filters\CriteriaFilterTrait;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        
        if(\Request::has('filterSect')){
            switch($_GET['filterSect']){
                case '7672':
                    CRUD::setModel(\App\Models\ACCESO_3G_Movil_Desempeño::class);
                    break;
                case '7676':
                    CRUD::setModel(\App\Models\ACCESO_3G_Movil_Desempeño_Sector::class);
                    break;
                case '7674':
                    CRUD::setModel(\App\Models\ACCESO_3G_Movil_Desempeño_Sitio::class);
                    break;
                case '7670':
                    CRUD::setModel(\App\Models\ACCESO_3G_Movil_Desempeño_Distrito::class);
                    break;
                case '7668':
                    CRUD::setModel(\App\Models\ACCESO_3G_Movil_Desempeño_Provincia::class);
                    break;
                case '7644':
                    CRUD::setModel(\App\Models\ACCESO_3G_Movil_Desempeño_Subregion::class);
                    break;
                case '7666':
                    CRUD::setModel(\App\Models\ACCESO_3G_Movil_Desempeño_Region::class);
                    break;
            }
        }else{
            CRUD::setModel(\App\Models\ACCESO_3G_Movil_Desempeño::class);
        }
        CRUD::setRoute(config('backpack.base.route_prefix') . '/acceso_3g_movil_desempeño/{tracingID}/{menuID}');
        CRUD::setEntityNameStrings('acceso_3g_movil_desempeño', 'ACCESO | 3G Movil | Desempeño');
        $this->loadFilters();
    }

    public function loadFilters(){
        $this->loadCriteriaFilter("SITE_ADDRESS","SITE_ADDRESS");
        $this->loadCriteriaFilter("CELLNAME","CELLNAME");
        $this->loadCriteriaFilter("SECTOR_NAME", "SECTOR_NAME");
        $this->loadCriteriaFilter("SITE_NAME", "SITE_NAME");
        $this->loadCriteriaFilter("DISTRITO", "DISTRITO");
        $this->loadCriteriaFilter("PROVINCIA", "PROVINCIA");
        $this->loadCriteriaFilter("SUB_REGION", "SUB_REGION");
        $this->loadCriteriaFilter("REGION", "REGION");
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        if(\Request::has('filterSect')){
            switch($_GET['filterSect']){
                case '7672':
                    $column = 'cellname';
                    $columnLabel = 'Cellname';
                    break;
                case '7676':
                    $column = 'sector_name';
                    $columnLabel = 'Sector Name';
                    break;
                case '7674':
                    $column = 'site_name';
                    $columnLabel = 'Site Name';
                    break;
                case '7670':
                    $column = 'distrito';
                    $columnLabel = 'Distrito';
                    break;
                case '7668':
                    $column = 'provincia';
                    $columnLabel = 'Provincia';
                    break;
                case '7644':
                    $column = 'sub_region';
                    $columnLabel = 'Sub region';
                    break;
                case '7666':
                    $column = 'region';
                    $columnLabel = 'Region';
                    break;
            }
        }else{
            $column = 'cellname';
            $columnLabel = 'Cellname';
        }

        \Backpack\CRUD\app\Library\Widget::add([
            'type' => "view",
            'view' => "backpack::base.inc.widgets.switch",
            'label' => 'Agrupación:',
            'text1' => 'Individual',
            'text2' => 'Agrupado'
        ])->to('before_content');

        $secids = array_column($this->crud->model->getCeldas(),'SECID');
        $sectnames = array_column($this->crud->model->getCeldas(),'SECTNAME');

        \Backpack\CRUD\app\Library\Widget::add([
            'type' => "view",
            'view' => "backpack::base.inc.widgets.select",
            'options' => $secids,
            'values' => $sectnames,
            'selected' => \Request::has('filterSect')?$_GET['filterSect']:7672
        ])->to('before_content');

        \Backpack\CRUD\app\Library\Widget::add([
            'type' => "view",
            'view' => "backpack::base.inc.widgets.tabs"
        ])->to('before_content');

        \Backpack\CRUD\app\Library\Widget::add([
            'type' => "view",
            'view' => "backpack::base.inc.widgets.highchart"
        ])->to('after_content');

        \Backpack\CRUD\app\Library\Widget::add([
            'type' => "view",
            'view' => "backpack::base.inc.widgets.imput_group"
        ])->to('after_content');

        $this->crud->addColumn([
            'name' => $column, // The db column name
            'label' => $columnLabel, // Table column heading
            'function_name' => 'getId',
            'type' => 'model_function',
            'limit' => 200
        ]);

        CRUD::setFromDb(); // columns

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */

        $this->crud->addClause('where', $column, '<>', "TEST");
        $this->crud->addClause('where', $column, '<>', "NULL");

        //$this->crud->enableExportButtons();

        $this->crud->addButtonFromView('line', 'verGrafica', 'verGrafica', 'beginning');
        $this->crud->addButtonFromView('line', 'agregarGrafica', 'agregarGrafica', 'end');
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(ACCESO_3G_Movil_DesempeñoRequest::class);

        CRUD::setFromDb(); // fields

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number'])); 
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
