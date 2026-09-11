<?php

namespace App\Http\Controllers\Admin\PrtltxAlarma;

use App\Models\PrtltxAlarma\DispMetaByTipoNodo;
use App\Models\PrtltxAlarma\PRTLTX_ALARMA_DISP_MES;
use App\Models\PrtltxAlarma\PRTLTX_DIAS_MES;
use App\Modules\PrtltxAlarma\Disponibilidad\Repository\DisponibilidadRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;

class PRTLTX_ALARMA_DASHBOARDController// extends ChartController
{
    private $repo;

    public function __construct(DisponibilidadRepository $repo)
    {
        $this->repo = $repo;
    }

    public function index(){
        
        $diasMes = PRTLTX_DIAS_MES::select('n_dias', DB::Raw("TO_CHAR(mes, 'YYYY-MM-DD') as mes"))->orderBy('mes')->get();
        $firstMes = count($diasMes) > 0 ? $diasMes[count($diasMes)-1]->mes : '';
        $mesIni = Carbon::createFromFormat('Y-m-d', $firstMes)->startOfMonth()->subMonths(5)->format('Y-m-d');
        $mesFin = Carbon::createFromFormat('Y-m-d', $firstMes)->startOfMonth()->subMonths(1)->format('Y-m-d');
        $response = $this->getDashboardData($mesIni, $mesFin);

        return view('backpack::prtltx_alarmas.dashboard', array_merge($response, [
            'diasMes' => $diasMes,
            'mesIni' => $mesIni,
            'mesFin' => $mesFin,
        ]));
    }

    public function getDashboardDataApi(Request $request){
        return $this->getDashboardData($request->get('mes_ini'),  $request->get('mes_fin'));
    }

    public function getDashboardData($mesIni, $mesFin){
        $dispGroupedByMes = $this->repo->getDispGroupedByMes($mesIni, $mesFin);

        $dispGroupedByMesAndTipoNodo = $this->repo->getDispGroupedByMesAndTipoNodo($mesIni, $mesFin);

        $dispGroupedByMesAndTipoNodoAndNeName = $this->repo->getDispGroupedByMesAndTipoNodoAndNeName($mesIni, $mesFin);

        $dispEsperadaByTipoNodo = DispMetaByTipoNodo::get();

        return [
            'dispGroupedByMes' => $dispGroupedByMes,
            'dispGroupedByMesAndTipoNodo' => $dispGroupedByMesAndTipoNodo,
            'dispGroupedByMesAndTipoNodoAndNeName' => $dispGroupedByMesAndTipoNodoAndNeName,
            'dispEsperadaByTipoNodo' => $dispEsperadaByTipoNodo
        ];
    }
}
