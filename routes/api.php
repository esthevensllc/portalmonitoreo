<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::group([
    'middleware' => ['amovil.auth']
], function () {
Route::get('chart', 'Api\V1\Admin\ChartsApiController@index')->name('api.chart');
Route::get('mapData', 'Api\V1\Admin\MapDataApiController@index')->name('api.mapData');
Route::post('mapDataKpiUbgTypeDataList', 'Api\V1\Admin\MapDataApiController@mapDataKpiUbgTypeDataList')->name('api.mapDataKpiUbgTypeDataList');
Route::get('mapDataGetDaByFatID', 'Api\V1\Admin\MapDataApiController@mapDataGetDaByFatID')->name('api.mapDataGetDaByFatID');
Route::post('dataMap', 'Api\V1\Admin\MapDataApiController@dataMap')->name('api.dataMap');
Route::post('mapDataKpiUbgType', 'Api\V1\Admin\MapDataApiController@mapDataKpiUbgType')->name('api.mapDataKpiUbgType');
Route::post('notifications/send-notification', [\App\Modules\Notifications\Controllers\SendNotificationController::class, '__invoke']);
Route::post('reporting-logs/error-log', [\App\Modules\ReportingLogs\ErrorLog\Controllers\SaveErrorLogController::class, '__invoke']);
Route::get('reporting-logs/{app_id}/error-log/{date}', [\App\Modules\ReportingLogs\ErrorLog\Controllers\GetErrorLogController::class, '__invoke']);
Route::get('reporting-logs/error-log/back-front', [\App\Modules\ReportingLogs\ErrorLog\Controllers\GetErrorLogController::class, 'errorLogBackFront']);

Route::get('getDataUbgProvByDptoCode', 'Api\V1\Admin\MapDataApiController@getDataUbgProvByDptoCode')->name('api.getDataUbgProvByDptoCode');
Route::get('getDataUbgDistByProvCode', 'Api\V1\Admin\MapDataApiController@getDataUbgDistByProvCode')->name('api.getDataUbgDistByProvCode');

Route::get('getDataMapCoverage', 'Api\V1\Admin\MapDataApiController@getDataMapCoverage')->name('api.getDataMapCoverage');
Route::get('getDataDownCoverage/{tracingID}/{menuID}/{dpto}/{prov}/{dist}/{OVERSHOOTER}/{PROBLEMA_ASOCIADO}/{scene}/{capacity}/{freqband}/{carrier}/{priority}/{changeVwMap}/{typeDown}', 'Api\V1\Admin\MapDataApiController@getDataDownCoverage')->name('api.getDataDownCoverage');
    
Route::get('getMapPenales', 'Api\V1\Admin\MapDataApiController@getMapPenales')->name('api.getMapPenales');
Route::get('getMapTest7', 'Api\V1\Admin\MapDataApiController@getMapTest7')->name('api.getMapTest7');

Route::get('getGraph', 'Api\V1\Admin\ChartsApiController@getGraph')->name('api.getDataGraph');
Route::get('dataGraph', 'Api\V1\Admin\ChartsApiController@dataGraph')->name('api.dataGraph');
Route::get('returnDataSubmenu', 'Api\V1\Admin\ChartsApiController@returnDataSubmenu')->name('api.returnDataSubmenu');
Route::get('getGraphBody', 'Api\V1\Admin\ChartsApiController@getGraphBody')->name('api.getGraphBody');
Route::get('changeMenuOpt', 'Api\V1\Admin\ChartsApiController@changeMenuOpt')->name('api.changeMenuOpt');
Route::get('graph', 'Api\V1\Admin\ChartsApiController@graph')->name('api.graph');

Route::get('getDataMapCoverageCellName', 'Api\V1\Admin\ChartsApiController@getDataMapCoverageCellName')->name('api.getDataMapCoverageCellName');
Route::get('chart', 'Api\V1\Admin\ChartsApiController@index')->name('api.chart');

Route::get('graphSurface', 'Api\V1\Admin\MapDataApiController@graphSurface')->name('api.graphSurface');
Route::get('getDatallePlanoTabla', 'Api\V1\Admin\MapDataApiController@getDatallePlanoTabla')->name('api.getDatallePlanoTabla');

Route::get('getListMapDataDet', 'Api\V1\Admin\MapDataApiController@getListMapDataDet')->name('api.getListMapDataDet');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('mapa-poligonos', [\App\Modules\MapaPoligonos\Controllers\MapaPoligonoController::class, 'get']);
Route::post('mapa-poligonos', [\App\Modules\MapaPoligonos\Controllers\MapaPoligonoController::class, 'create']);
});
*/
