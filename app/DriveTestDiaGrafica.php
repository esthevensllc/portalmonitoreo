<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DriveTestDiaGrafica extends Model
{
    //use SoftDeletes;

    public $table = 'PRG_MTV_DTEST';

    protected $fillable = [
        'throughput_dl_kbps',
        'timestamp',
        'idcell_dl'
    ];
}