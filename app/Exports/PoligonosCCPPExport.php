<?php

namespace App\Exports;

use App\PoligonosCCPP;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;
use App\Facads\ODB;

class PoligonosCCPPexport implements FromArray, WithHeadings
{
    use Exportable;
    public function __construct($tracingID, $typeUbg, $mapGroupSel, $mapGroupUnit)
    {

        if($mapGroupUnit == '0' || $mapGroupUnit == 0){
            $mapGroupUnit = 'NULL';
        }
        $sql = "BEGIN PSO_OSSPORTAL.SP_GET_MAPUBGTYPETRACID(".$tracingID.", ".$typeUbg.", ".$mapGroupSel.", ".$mapGroupUnit.", :resultado); END;";  

        $rows = $this->executeProcedure($sql);

        foreach ($rows as $key => $row) {
            $result[$row["CODE"]] = $row;
        }

        $this->dataTbl  =  $result;

		$this->headTbl = array_keys(end($this->dataTbl));

    }

    public function headings(): array
    {
        return $this->headTbl;
    }

    public function  array(): array
    {
        return $this->dataTbl;
    }

    public function executeProcedure($sql){

        $data = DB::transaction(function($conn) use ($sql){
            $pdo = $conn->getPdo();
            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(':resultado', $lista, ODB::CURSOR);

            $stmt->execute();

            oci_execute($lista, OCI_DEFAULT);
            oci_fetch_all($lista, $array, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
            oci_free_cursor($lista);

            return $array;
        });

        return $data;
    }

}
