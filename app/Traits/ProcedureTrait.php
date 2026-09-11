<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use PDO;

trait ProcedureTrait
{
    public function executeProcedure($sql, $params = [])
    {
        $data = DB::transaction(function($conn) use ($sql, $params){
            $pdo = $conn->getPdo();
            $stmt = $pdo->prepare($sql);

			if(count($params) > 0){
				foreach ($params as $name => $row) {
					if(!in_array($name, ["myCursor"])){
						$stmt->bindParam(':'.$name, $row["value"], $row["type"]);	
					}else{
						$stmt->bindParam(':myCursor', $lista, PDO::PARAM_STMT);
					}
				}
			}else{
				$stmt->bindParam(':resultado', $lista, PDO::PARAM_STMT);
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
