<?php

namespace App\Models\Desempenio;

use App\Facads\ODB;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use DB;

class SharedModel extends Model
{
    use CrudTrait;
    
    //private $table_to_set = null;
    //private $fields_to_set = null;

    protected $table = PSO_BASE_TABLE;
    public $primaryKey = PSO_BASE_TABLE_ID;
    protected $fillable = [];
    public $timestamps = false;
    public $incrementing = false;

    private $ids = PSO_BASE_TABLE_IDS;

    public function setTable($table){
        $this->table = $table;
    }

    public function setFields($fillable){
        $this->fillable = $fillable;
    }

    public function setIds($ids){
        $this->ids = $ids;
	$this->primaryKey = $ids[0];
    }

    public function getId(){
        $ids = [];
        foreach($this->ids as $part){
            $ids[] = $this->{$part};
        }
        return implode("__", $ids);
    }

    public function getIds(){
        $ids = [];
        foreach($this->ids as $part){
            $ids[] = $this->{$part};
        }
        return $ids;
    }

    public function getCeldas(int $id_section){
        $sql = "BEGIN PSO_OSSPORTAL.SP_GET_MENUDATAFILBYSECTID($id_section, :resultado); END;";       
        $dataSections = $this->executeProcedure($sql);
        return $dataSections;
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
