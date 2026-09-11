<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TopCelUlDiaGrafica extends Model
{
    //use SoftDeletes;

    public $table = 'PRG_MTV_CVM_OP_TOP_CELLUL';

    protected $fillable = [
        'throughput_ul_kbps',
        'timestamp',
        'idcell_ul'
    ];
}