<?php
namespace App\Http\Controllers\Admin;

use AMovil\Modules\PsoModules\Domain\PsoModuleRepository;
use AMovil\Shared\Exports\Domain\Writer;
use AMovil\Shared\Exports\Domain\WriterType;
use App\Facads\ODB;
use App\Repository\FijaCoverageTopRepository;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MapFijaCoverageController
{
	private $configRepository;

	public function index($id_tracing)
    {
		$tracingID = $id_tracing;
        $menuID = "6748";
        $title = "";
        $id_profile = null;
        $roles = backpack_user()->roles;
        // para filtros generacion de ano y semana
        $ano = DB::select("select ano, MAX(semana) max_semana from FIJA_PLANOS_INDICADORES_SEMANAL group by ano order by ano desc");
        $planoUrl = "map_" . $tracingID . "_" . $menuID;
        $kpis = $this->executeProcedure("BEGIN PK_FIJA_ANALISIS.SP_OBTENER_FILTROS_MAPA(:v_nivel, :resultado); END;", [
			"v_nivel" => ["value" => 4, "type" => ODB::CHAR],
			"resultado" => ["type" => ODB::CURSOR],
		]);
        $kpis = json_decode(json_encode($kpis));
        //obtener lista de departamentos
        $dptoList = $this->executeProcedure("BEGIN PSO_OSSPORTAL.SP_GET_UBGDPTO(:resultado); END;");
        
        $competencias = null;
        $detailFieldsByDistribucion = null;
        return view("backpack::mapFijaCoverage", compact("tracingID", "menuID", "title", "ano", "planoUrl", "kpis", "dptoList", "competencias", "detailFieldsByDistribucion"));
    }

    public function executeProcedure($sql, $params = [])
    {
        return DB::transaction(function($conn) use ($sql, $params){
            $pdo = $conn->getPdo();
            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(':resultado', $lista, ODB::CURSOR);

            foreach ($params as $name => $row) {
                if ($name !== "resultado") {
                    $stmt->bindParam(':'.$name, $row["value"], $row["type"]);
                }
            }

            $stmt->execute();

            oci_execute($lista, OCI_DEFAULT);
            oci_fetch_all($lista, $array, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);
            oci_free_cursor($lista);

            return $array;
        });
    }
}
