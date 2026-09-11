<?php

namespace App\Traits\DB;

use App\Facads\ODB;
use Illuminate\Support\Facades\DB;

trait ProcedureTrait
{
    public function executeProcedure($sql, $params = [])
    {
        $data = DB::transaction(function($conn) use ($sql, $params){
            $pdo = $conn->getPdo();
            $stmt = $pdo->prepare($sql);

			if(count($params) > 0){
				foreach ($params as $name => $row) {
					if(!in_array($name, ["resultado"])){
						$stmt->bindParam(':'.$name, $row["value"], $row["type"]);	
					}else{
						$stmt->bindParam(':resultado', $lista, ODB::CURSOR);
					}
				}
			}else{
				$stmt->bindParam(':resultado', $lista, ODB::CURSOR);
			}


            $stmt->execute();

            oci_execute($lista, OCI_DEFAULT);
            oci_fetch_all($lista, $array, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
            oci_free_cursor($lista);

            return $array;
        });

        return $data;
    }
}
