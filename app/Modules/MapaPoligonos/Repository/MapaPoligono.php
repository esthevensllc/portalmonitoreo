<?php

namespace App\Modules\MapaPoligonos\Repository;

use Illuminate\Database\Eloquent\Model;

class MapaPoligono extends Model
{
    protected $table = 'padm_mapa_poligono';
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $timestamps = true;

    // public $sequence = 'seq_padm_mapa_poligono';

    protected $fillable = [
        'id',
        'nombre',
        'observacion',
        'responsable',
        'poligono',
        'created_at',
        'updated_at',
    ];

    public static function table(){
        return 'padm_mapa_poligono';
    }
}
