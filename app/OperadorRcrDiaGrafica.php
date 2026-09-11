<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OperadorRcrDiaGrafica extends Model
{
    //use SoftDeletes;

    public $table = 'PRG_MTV_CVM_MSTRS_RECR_DIA';

    protected $fillable = [
        'ubigeo',
        'timestamp'
    ];
}