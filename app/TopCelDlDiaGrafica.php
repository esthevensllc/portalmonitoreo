<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TopCelDlDiaGrafica extends Model
{
    //use SoftDeletes;

    public $table = 'PRG_MTV_CVM_OP_TOP_CELLDL';

    protected $fillable = [
        'throughput_dl_kbps',
        'timestamp',
        'idcell_dl'
    ];
}