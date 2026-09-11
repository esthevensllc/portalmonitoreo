<?php

namespace App\Models;

use App\Facads\ODB;
use Illuminate\Support\Facades\DB;

class OracleDatabase extends Oci8Connection
{

    public function executeProcedure_($procedureName, array $bindings = []){
        $pdo = DB::getPdo();
        $paramNames = implode(',:',array_pluck($bindings,'name'));
        $stmt = $pdo->prepare("begin $procedureName(:$paramNames); end;");
        $cursors = [];
        $result = [];

        foreach ($bindings as $key => $row){
             // binding input parameters.
            if(isset($row['value']))
                $stmt->bindParam(":".$row['name'], $row['value']);
            
            // binding cursor output parameters.
            else if($row['type'] === ODB::CURSOR)
                $stmt->bindParam(":".$row['name'],  $cursors[$row['name']], ODB::CURSOR);

            // binding other none cursor output parameters.
            else
                $stmt->bindParam(":".$row['name'],  $result[$row['name']], $row['type']);
        }

        $stmt->execute();

        //fetch cursors and put them inside result
        foreach($cursors as $key => $cursor){
            oci_execute($cursor, OCI_DEFAULT);
            oci_fetch_all($cursor, $result[$key], 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
            oci_free_cursor($cursor);
        }

        return $result;
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