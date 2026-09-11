<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TopImsiDiaGrafica extends Model
{
    //use SoftDeletes;

    public $table = 'PRG_MTV_CVM_OP_TOP_IMSI';

    protected $fillable = [
        'throughput_dl_kbps',
        'throughput_ul_kbps',
        'timestamp',
        'imsi'
    ];
}