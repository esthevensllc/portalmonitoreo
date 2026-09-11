<?php

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
    ),
    'namespace'  => 'App\Http\Controllers\Admin',
], function () {
    Route::post('login', [App\Http\Controllers\Auth\CustomLoginController::class, 'login']);
});

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    /*Route::crud('acceso_2g_movil_provision/{tracingID}/{menuID}', 'ACCESO_2G_Movil_ProvisionCrudController');
    Route::crud('acceso_3g_movil_provision/{tracingID}/{menuID}', 'ACCESO_3G_Movil_ProvisionCrudController');
    Route::crud('acceso_4g_movil_provision/{tracingID}/{menuID}', 'ACCESO_4G_Movil_ProvisionCrudController');
    Route::crud('acceso_4g_fijo_provision/{tracingID}/{menuID}', 'ACCESO_4G_Tdd_ProvisionCrudController');
    Route::crud('acceso_5g_movil_provision/{tracingID}/{menuID}', 'ACCESO_5G_Movil_ProvisionCrudController');
    Route::get('acceso_2g_movil_provision/export', 'ACCESO_2G_Movil_ProvisionCrudController@export');
    Route::get('acceso_3g_movil_provision/export', 'ACCESO_3G_Movil_ProvisionCrudController@export');
    Route::get('acceso_4g_movil_provision/export', 'ACCESO_4G_Movil_ProvisionCrudController@export');
    Route::get('acceso_4g_fijo_provision/export', 'ACCESO_4G_Tdd_ProvisionCrudController@export');
    Route::get('acceso_5g_movil_provision/export', 'ACCESO_5G_Movil_ProvisionCrudController@export');
    

    Route::get('acceso_2g_movil_resumen/{tracingID}/{menuID}', 'MapController@index');
    Route::get('acceso_3g_movil_resumen/{tracingID}/{menuID}', 'MapController@index');
    Route::get('acceso_4g_movil_resumen/{tracingID}/{menuID}', 'MapController@index');
    Route::get('acceso_fija_resumen/{tracingID}/{menuID}', 'MapController@index');
    Route::get('acceso_5g_movil_resumen/{tracingID}/{menuID}', 'MapController@index');*/
    // Route::get('poligonos/export/{typeMapSeg}/{tracingID}/{menuID}/{mapGroupSel}/{mapGroupUnit}', 'MapController@exportPoli');

    Route::get('acceso_4g_movil_cobertura/{tracingID}/{menuID}', 'MapController@mapCoverage');
    Route::post('acceso_4g_movil_cobertura/{tracingID}/{menuID}', 'MapController@mapCoverage');
    Route::post('getDataUbgProvByDptoCode/{CodeUbgSlc}', 'MapController@getDataUbgProvByDptoCode');

    Route::get('acceso_5g_movil_cobertura/getDataDownCoverage/{dpto}/{prov}/{dist}/{OVERSHOOTER}/{PROBLEMA_ASOCIADO}/{scene}/{capacity}/{freqband}/{carrier}/{priority}/{changeVwMap}/{typeDown}', 'Acceso\Tec5GMovil\Map5GMovilCoberturaController@getDataDownCoverage');
    Route::get('acceso_5g_movil_cobertura/{tracingID}/{menuID}', 'Acceso\Tec5GMovil\Map5GMovilCoberturaController@mapCoverage');
    Route::get('acceso_5g_movil_cobertura/{tracingID}/{menuID}/getDataMapCoverage', 'Acceso\Tec5GMovil\Map5GMovilCoberturaController@getDataMapCoverage');
    Route::get('acceso_5g_movil_cobertura/{tracingID}/{menuID}/getDataMapCoverageCellName', 'Acceso\Tec5GMovil\Map5GMovilCoberturaController@getDataMapCoverageCellName');
    Route::get('acceso_5g_movil_cobertura/{tracingID}/{menuID}/graphSurface', 'Acceso\Tec5GMovil\Map5GMovilCoberturaController@graphSurface');
    Route::get('acceso_5g_movil_cobertura/{tracingID}/{menuID}/getListMapDataDet', 'Acceso\Tec5GMovil\Map5GMovilCoberturaController@getListMapDataDet');

    /*Route::get('acceso_fija_cobertura/{tracingID}/{menuID}', 'MapController@mapCoverage');
    Route::post('acceso_fija_cobertura/{tracingID}/{menuID}', 'MapController@mapCoverage');

    Route::crud('acceso_2g_movil_desempeño/{tracingID}/{menuID}', 'ACCESO_2G_Movil_DesempeñoCrudController');
    Route::crud('acceso_3g_movil_desempeño/{tracingID}/{menuID}', 'ACCESO_3G_Movil_DesempeñoCrudController');
    Route::crud('acceso_4g_movil_desempeño/{tracingID}/{menuID}', 'ACCESO_4G_Movil_DesempeñoCrudController');
    Route::crud('acceso_4g_tdd_desempeño/{tracingID}/{menuID}', 'ACCESO_4G_Tdd_DesempeñoCrudController');
    Route::crud('acceso_5g_movil_desempeño/{tracingID}/{menuID}', 'ACCESO_5G_Movil_DesempeñoCrudController');*/

    // Route::get('transporte_calidad_tx_resumen/{tracingID}/{menuID}', 'MapController@index');
    Route::group(['middleware' => ['check.permission'], 'menuId' => '355'], function () {
        Route::get('resumen/{tracingID}/{menuID}', 'MapController@index');
        Route::get('poligonos/export/{typeMapSeg}/{tracingID}/{menuID}/{mapGroupSel}/{mapGroupUnit}', 'MapController@exportPoli');
    });
    
    Route::group(['middleware' => ['check.permission'], 'menuId' => '16'], function () {
        Route::crud('desemp/{tracingID}/{menuID}/{sectID?}', 'DesempenioController');
    });
    /*Route::crud('transporte_calidad_tx_desempeño/{tracingID}/{menuID}', 'DesempenioController');
    Route::crud('transporte_transmisión_desempeño/{tracingID}/{menuID}', 'DesempenioController');
    Route::crud('transporte_transporte_kpi3_desempeño/{tracingID}/{menuID}', 'DesempenioController');
    Route::crud('transporte_transporte_kpi4_desempeño/{tracingID}/{menuID}', 'DesempenioController');*/

    /*Route::crud('cobertura_cobertura_movil_detalle/{tracingID}/{menuID}', 'COBERTURA_Cobertura_Movil_DetalleCrudController');
    Route::get('cobertura_cobertura_movil_detalle/export', 'COBERTURA_Cobertura_Movil_DetalleCrudController@export');

    Route::get('cobertura_cobertura_movil_operadoras/{tracingID}/{menuID}', 'COBERTURA_MOVIL_OPERADORAS@index');
    Route::get('charts/cobertura_movil_operadoras', 'Charts\COBERTURAMOVILOPERADORASChartController@response')->name('charts.cobertura_movil_operadoras.index');*/

    Route::group(['middleware' => ['check.permission'], 'menuId' => '15'], function () {
        Route::crud('alarmas/{id_tracing}', 'AlarmasController');
        Route::get('alarmas/{id_tracing}/export', 'AlarmasController@export');
    });
    
    Route::group(['middleware' => ['check.permission'], 'menuId' => '13'], function () {
        Route::crud('provision/{id_tracing}', 'ProvisionController');
        Route::get('provision/{id_tracing}/export', 'ProvisionController@export');
    });

    Route::group([
        'middleware' => ['check.permission'], 'tracingId' => '19', 'menuId' => '6748',
    ], function () {
        Route::get('fija-coverage/semana-by-ano/{ano}', 'MapFijaCoverageController@get_semana_by_ano');
        Route::post('fija-coverage/data-fija', 'MapFijaCoverageController@get_data_fija');
        Route::post('fija-coverage/data-fija/export', 'MapFijaCoverageController@exportDataFija');
        Route::get('fija-coverage/data-fija/export', 'MapFijaCoverageController@exportDataFija');
        Route::post('fija-coverage/data-fija-by-name/{distribucion_id}/{kpi_id}', 'MapFijaCoverageController@get_data_fija_by_name');
        
        // Route::post('fija-coverage/cobertura-otros', 'MapFijaCoverageController@get_cobertura_otros');
        // Route::get('fija-coverage/cobertura-otros-geojson', 'MapFijaCoverageController@get_cobertura_otros_geojson');
        Route::get('fija-coverage/departamentos/{dptoCode}/provincias', 'MapFijaCoverageController@getDataUbgProvByDptoCode');
        Route::get('fija-coverage/departamentos/provincias/{provCode}/distritos', 'MapFijaCoverageController@getDataUbgDistByProvCode');
        // Route::get('fija-coverage/graph-data', 'MapFijaCoverageController@getExtraGraphData');
        Route::get('fija-coverage/table-data', 'MapFijaCoverageController@getTableData');
        // Route::get('fija-coverage/sub-table-data', 'MapFijaCoverageController@getSubTableData');
        // Route::get('fija-coverage/verticales-liberados', 'MapFijaCoverageController@getVerticalesLiberados');
        // Route::get('fija-coverage/top-sites-tethering', 'MapFijaCoverageController@getTopSitesTethering');
        // // Route::get('fija-coverage/{distribucion_id}/{kpi_id}/top-data', 'MapFijaCoverageController@getTopTable');
        Route::get('fija-coverage/ubigeos', 'MapFijaCoverageController@getUbigeos');
        Route::get('fija-coverage/{id_tracing}', 'MapFijaCoverageController@index');
        Route::get('getDatallePlanoTabla', '\App\Http\Controllers\Api\V1\Admin\MapDataApiController@getDatallePlanoTabla')->name('api.getDatallePlanoTabla');
    });

    Route::group(['middleware' => ['check.permission'], 'menuId' => '10804'], function () {
        Route::crud('empresas/{id_tracing}', 'EmpresasController');
        Route::get('empresas/{id_tracing}/export', 'EmpresasController@export');
        Route::get('empresas/{id_tracing}/exportDataEmpresas/{idProceso}', 'EmpresasController@exportDataEmpresas');
        Route::post('empresas/{id_tracing}/import', 'EmpresasController@import');
    });

    Route::group(['middleware' => ['check.permission'], 'menuId' => '10142'], function () {
        Route::crud('detalle/{id_tracing}', 'DetalleController');
        Route::get('detalle/{id_tracing}/export', 'DetalleController@export');
    });

    Route::group(['middleware' => ['check.permission'], 'menuId' => '10143'], function () {
        Route::get('operadoras/{id_tracing}', 'OperadorasController@view');
        Route::get('api/operadoras/{id_tracing}', 'OperadorasController@api');
        Route::get('operadoras/{id_tracing}/export', 'OperadorasController@export');
    });

    Route::group(['middleware' => ['check.permission'], 'menuId' => '14'], function () {
        Route::crud('configuracion/{id_tracing}', 'ConfiguracionController');
        Route::get('configuracion/{id_tracing}/export', 'ConfiguracionController@export');
    });

    Route::crud('replist/{id_tracing}/{menu_id}', 'ReportListController');
    Route::get('replist/{id_tracing}/{menu_id}/export', 'ReportListController@export');

    Route::post('filesdown/validFileExist', 'FilesDownController@validFileExist');
    Route::get('filesdown/validProcess', 'FilesDownController@validProcess');
    Route::post('filesdown/loadProcess', 'FilesDownController@loadProcess');
    Route::get('filesdown/report/{nroReport}/{fechaVal}/{pag?}', 'ExportFilesDownController@report');
    // reports test 3
    Route::get('filesdown/export/exp-interrupciones-cpe-excel-inc/{dateYearMonth}', 'ExportFilesDownController@expInterrupcionesCpeExcelInc');
    Route::get('filesdown/export/exp-interrupciones-cpe-excel/{dateYearMonth}', 'ExportFilesDownController@expInterrupcionesCpeExcel');
    Route::get('filesdown/export/exp-intranet-excel-inc/{dateYearMonth}', 'ExportFilesDownController@expIntranetExcelInc');
    Route::get('filesdown/export/exp-intranet-excel/{dateYearMonth}', 'ExportFilesDownController@expIntranetExcel');
    Route::get('filesdown/export/exp-int-sub-sistema-excel-inc/{dateYearMonth}', 'ExportFilesDownController@expIntSubSistemaExcelInc');
    Route::get('filesdown/export/exp-int-sub-sistema-excel/{dateYearMonth}', 'ExportFilesDownController@expIntSubSistemaExcel');
    Route::get('filesdown/export/exp-int-pop-excel-inc/{dateYearMonth}', 'ExportFilesDownController@expIntPopExcelInc');
    Route::get('filesdown/export/exp-int-pop-excel/{dateYearMonth}', 'ExportFilesDownController@expIntPopExcel');

    Route::get('filesdown/{id_tracing}', 'FilesDownController@view');

    /*Route::get('buscar-direccion/{id_tracing}', 'BusquedaDireccionController@view');
    Route::get('buscar-direccion/{id_tracing}/ConsultarCoberturaWS', 'BusquedaDireccionController@getCobertura');

    Route::get('buscar-casa-direccion/{id_tracing}', 'BusquedaDireccionController@view');
    Route::get('buscar-casa-coordenada/{id_tracing}', 'BusquedaCasaCoordController@view');
    Route::get('buscar-casa-plano/{id_tracing}', 'BusquedaCasaPLanoController@view');
    Route::get('buscar-casa-plano/planos/{plano}', 'BusquedaCasaPLanoController@gePlanos');
    Route::get('buscar-edificios/{id_tracing}', 'BusquedaEdificioController@view');
    Route::get('buscar-edificios/by-ubigeo/{ubigeo}', 'BusquedaEdificioController@getEdificiosByUbigeo');

    Route::crud('sots/{id_tracing}', 'SotsController');
    Route::get('sots/{id_tracing}/export', 'SotsController@export');
    Route::get('sots/{id_tracing}/busqueda-fats', 'SotsController@busquedaFatsView');
    Route::get('sots/{id_tracing}/summary', 'SotsController@getSummary');
    Route::get('sots/{id_tracing}/estados', 'SotsController@getEstados');*/

    Route::group(['middleware' => ['check.permission'], 'tracingId' => '19', 'menuId' => '12026'], function () {
        Route::get('operacion-fija/dashboard/tipos-respaldo-by-region', [\AMovil\Fija\OperacionFija\Controllers\FuenteFijaDashboardController::class, "getNumTipoRespaldoByRegion"]);
        Route::get('operacion-fija/dashboard/tipos-respaldo-by-month', [\AMovil\Fija\OperacionFija\Controllers\FuenteFijaDashboardController::class, "getNumTipoRespaldoByMonth"]);
        Route::get('operacion-fija/dashboard/{id_tracing}', [\AMovil\Fija\OperacionFija\Controllers\FuenteFijaDashboardController::class, "view"]);
        
        Route::post('operacion-fija/enlaces', [\AMovil\Fija\OperacionFijaPolyline\Controllers\OperacionFijaEnlaceController::class, "create"]);
        Route::get('operacion-fija/enlaces', [\AMovil\Fija\OperacionFijaPolyline\Controllers\OperacionFijaEnlaceController::class, "get"]);
        Route::delete('operacion-fija/enlaces/{id}', [\AMovil\Fija\OperacionFijaPolyline\Controllers\OperacionFijaEnlaceController::class, "delete"]);

        Route::get('operacion-fija/fuentes-hfc', 'OperacionFijaController@getFuentesHfc');
        Route::get('operacion-fija/{id_tracing}', 'OperacionFijaController@view');
        Route::get('api/operacion-fija/export', [\AMovil\Fija\OperacionFija\Controllers\FuenteFijaController::class, "export"]);
        Route::get('api/operacion-fija/{id}', [\AMovil\Fija\OperacionFija\Controllers\FuenteFijaController::class, "find"]);
        Route::post('api/operacion-fija', [\AMovil\Fija\OperacionFija\Controllers\FuenteFijaController::class, "create"]);
        Route::put('api/operacion-fija', [\AMovil\Fija\OperacionFija\Controllers\FuenteFijaController::class, "update"]);
        Route::post('api/operacion-fija/images', [\AMovil\Fija\OperacionFija\Controllers\FuenteFijaController::class, "updateImages"]);
        Route::delete('api/operacion-fija/{id}', [\AMovil\Fija\OperacionFija\Controllers\FuenteFijaController::class, "delete"]);
    });

    Route::group(['middleware' => ['check.permission'], 'menuId' => '12106'], function () {
        Route::crud('optimizacion-bosf/{id_tracing}', 'Acceso\Fija\OptimizacionBOSFController');
        Route::get('optimizacion-bosf/{id_tracing}/export', 'Acceso\Fija\OptimizacionBOSFController@export');
        Route::get('optimizacion-bosf/{id_tracing}/modal', 'Acceso\Fija\OptimizacionBOSFController@modal');
        Route::get('optimizacion-bosf/{id_tracing}/export-detalle', 'Acceso\Fija\OptimizacionBOSFController@exportDetalle');
    });

    Route::group(['middleware' => ['check.permission'], 'tracingId' => '34', 'menuId' => '12250'], function () {
        Route::get('afectacion_energia/sitios', [\AMovil\Maps\AfectacionEnergia\Controllers\AfectacionEnergiaController::class, "getSites"]);
        Route::get('afectacion_energia/sitios-caidos', [\AMovil\Maps\AfectacionEnergia\Controllers\AfectacionEnergiaController::class, "getCantSitiosCaidos"]);
        Route::get('afectacion_energia/ubigeos/{ubigeo}', [\AMovil\Maps\AfectacionEnergia\Controllers\AfectacionEnergiaController::class, "findDetalleEnergiaByUbigeo"]);
        Route::get('afectacion_energia/{id_tracing}', [\AMovil\Maps\AfectacionEnergia\Controllers\AfectacionEnergiaController::class, "view"]);
    });
    Route::group(['middleware' => ['check.permission'], 'tracingId' => '36', 'menuId' => '12551'], function () {
        Route::get('ookla-map/kpis/months', [\AMovil\Maps\OoklaMap\Controllers\OoklaMaController::class, "getKpiMonths"]);
        Route::get('ookla-map/kpis/operators', [\AMovil\Maps\OoklaMap\Controllers\OoklaMaController::class, "getKpiOperators"]);
        Route::get('ookla-map/kpis/last-months', [\AMovil\Maps\OoklaMap\Controllers\OoklaMaController::class, "getKpiLastMonths"]);
        Route::get('ookla-map/kpis/{month}/{operator}/{kpiName}', [\AMovil\Maps\OoklaMap\Controllers\OoklaMaController::class, "getByMonthAndOperatorAndKpiname"]);
        Route::get('ookla-map/{id_tracing}', [\AMovil\Maps\OoklaMap\Controllers\OoklaMaController::class, "view"]);
    });
    Route::group(['middleware' => ['check.permission'], 'tracingId' => '36', 'menuId' => '12590'], function () {
        Route::get('ookla-map-fija/kpis/months', [\AMovil\Maps\OoklaMapFija\Controllers\OoklaMapFijaController::class, "getKpiMonths"]);
        Route::get('ookla-map-fija/kpis/operators', [\AMovil\Maps\OoklaMapFija\Controllers\OoklaMapFijaController::class, "getKpiOperators"]);
        Route::get('ookla-map-fija/kpis/last-months', [\AMovil\Maps\OoklaMapFija\Controllers\OoklaMapFijaController::class, "getKpiLastMonths"]);
        Route::get('ookla-map-fija/kpis/{month}/{operator}/{kpiName}', [\AMovil\Maps\OoklaMapFija\Controllers\OoklaMapFijaController::class, "getByMonthAndOperatorAndKpiname"]);
        Route::get('ookla-map-fija/{id_tracing}', [\AMovil\Maps\OoklaMapFija\Controllers\OoklaMapFijaController::class, "view"]);
    });
}); // this should be the absolute last line of this file

Route::group([
    'prefix' => 'api',
    'middleware' => ['web','amovil.auth'],
    'namespace'  => 'App\Http\Controllers',
], function () {
    Route::get('chart', 'Api\V1\Admin\ChartsApiController@index')->name('api.chart');
    
    // mapa resumen
    Route::get('mapDataGetDaByFatID', 'Api\V1\Admin\MapDataApiController@mapDataGetDaByFatID')->name('api.mapDataGetDaByFatID');
    Route::group(['middleware' => ['check.permission'], 'menuId' => '355'], function () {
        Route::post('mapDataKpiUbgTypeDataList', 'Api\V1\Admin\MapDataApiController@mapDataKpiUbgTypeDataList')->name('api.mapDataKpiUbgTypeDataList');
        Route::post('dataMap', 'Api\V1\Admin\MapDataApiController@dataMap')->name('api.dataMap');
        Route::post('mapDataKpiUbgType', 'Api\V1\Admin\MapDataApiController@mapDataKpiUbgType')->name('api.mapDataKpiUbgType');
    });

    // rep logs
    Route::post('notifications/send-notification', [\App\Modules\Notifications\Controllers\SendNotificationController::class, '__invoke']);
    Route::post('reporting-logs/error-log', [\App\Modules\ReportingLogs\ErrorLog\Controllers\SaveErrorLogController::class, '__invoke']);
    Route::get('reporting-logs/{app_id}/error-log/{date}', [\App\Modules\ReportingLogs\ErrorLog\Controllers\GetErrorLogController::class, '__invoke']);
    Route::get('reporting-logs/error-log/back-front', [\App\Modules\ReportingLogs\ErrorLog\Controllers\GetErrorLogController::class, 'errorLogBackFront']);

    // map coverage
    Route::get('mapData', 'Api\V1\Admin\MapDataApiController@index')->name('api.mapData');

    Route::get('getDataUbgProvByDptoCode', 'Api\V1\Admin\MapDataApiController@getDataUbgProvByDptoCode')->name('api.getDataUbgProvByDptoCode');
    Route::get('getDataUbgDistByProvCode', 'Api\V1\Admin\MapDataApiController@getDataUbgDistByProvCode')->name('api.getDataUbgDistByProvCode');

    Route::get('getDataMapCoverage', 'Api\V1\Admin\MapDataApiController@getDataMapCoverage')->name('api.getDataMapCoverage');
    Route::get('getDataDownCoverage/{tracingID}/{menuID}/{dpto}/{prov}/{dist}/{OVERSHOOTER}/{PROBLEMA_ASOCIADO}/{scene}/{capacity}/{freqband}/{carrier}/{priority}/{changeVwMap}/{typeDown}', 'Api\V1\Admin\MapDataApiController@getDataDownCoverage')->name('api.getDataDownCoverage');
        
    Route::get('getMapPenales', 'Api\V1\Admin\MapDataApiController@getMapPenales')->name('api.getMapPenales');
    Route::get('getMapTest7', 'Api\V1\Admin\MapDataApiController@getMapTest7')->name('api.getMapTest7');
    
    Route::get('getDataMapCoverageCellName', 'Api\V1\Admin\ChartsApiController@getDataMapCoverageCellName')->name('api.getDataMapCoverageCellName');
    Route::get('graphSurface', 'Api\V1\Admin\MapDataApiController@graphSurface')->name('api.graphSurface');

    Route::get('getListMapDataDet', 'Api\V1\Admin\MapDataApiController@getListMapDataDet')->name('api.getListMapDataDet');
    // fin coverage

    // desempeño charts
    Route::get('getGraph', 'Api\V1\Admin\ChartsApiController@getGraph')->name('api.getDataGraph');
    Route::get('dataGraph', 'Api\V1\Admin\ChartsApiController@dataGraph')->name('api.dataGraph');
    Route::get('returnDataSubmenu', 'Api\V1\Admin\ChartsApiController@returnDataSubmenu')->name('api.returnDataSubmenu');
    Route::get('changeMenuOpt', 'Api\V1\Admin\ChartsApiController@changeMenuOpt')->name('api.changeMenuOpt');
    Route::group(['middleware' => ['check.permission_graph'], 'menuId' => '355'], function () {
        Route::get('getGraphBody', 'Api\V1\Admin\ChartsApiController@getGraphBody')->name('api.getGraphBody');
        Route::get('graph', 'Api\V1\Admin\ChartsApiController@graph')->name('api.graph');
    });

    // Route::get('chart', 'Api\V1\Admin\ChartsApiController@index')->name('api.chart');// portal regulatorio
    
    // fija coverage 19
    // Route::get('getDatallePlanoTabla', 'Api\V1\Admin\MapDataApiController@getDatallePlanoTabla')->name('api.getDatallePlanoTabla');

    Route::middleware('auth:api')->get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('mapa-poligonos', [\App\Modules\MapaPoligonos\Controllers\MapaPoligonoController::class, 'get']);
    Route::post('mapa-poligonos', [\App\Modules\MapaPoligonos\Controllers\MapaPoligonoController::class, 'create']);
});
